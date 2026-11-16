<?php

namespace App\Livewire\Sales;

use App\Models\ReturnModel;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class History extends Component
{
    use WithPagination;

    public string $search = '';

    public string $from = '';

    public string $to = '';

    public string $paymentStatus = '';

    private const PRESETS = ['today', '7d', '30d', 'all'];

    public function mount(): void
    {
        $this->setRange('30d');
    }

    public function updated(string $name): void
    {
        if (in_array($name, ['search', 'from', 'to', 'paymentStatus'], true)) {
            $this->resetPage();
        }
    }

    public function setRange(string $preset): void
    {
        [$this->from, $this->to] = $this->rangeFor($preset);
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->paymentStatus = '';
        $this->setRange('30d');
    }

    private function rangeFor(string $preset): array
    {
        $today = now()->toDateString();

        return match ($preset) {
            'today' => [$today, $today],
            '7d' => [now()->subDays(6)->toDateString(), $today],
            '30d' => [now()->subDays(29)->toDateString(), $today],
            default => ['', ''],
        };
    }

    private function activeRange(): string
    {
        foreach (self::PRESETS as $preset) {
            if ($this->rangeFor($preset) === [$this->from, $this->to]) {
                return $preset;
            }
        }

        return '';
    }

    private function baseQuery(int $storeId): Builder
    {
        $from = $this->from;
        $to = $this->to;

        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
        }

        return Sale::forStore($storeId)
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->where(fn ($w) => $w
                    ->where('code', 'like', $term)
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', $term)));
            })
            ->when($this->paymentStatus, fn ($q) => $q->where('payment_status', $this->paymentStatus))
            ->when($from, fn ($q) => $q->where('sold_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn ($q) => $q->where('sold_at', '<=', Carbon::parse($to)->endOfDay()));
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $base = $this->baseQuery($storeId);
        $returnsBySale = ReturnModel::forStore($storeId)
            ->where('status', 'approved')
            ->select('sale_id', DB::raw('SUM(refund_amount) as refund_total'))
            ->groupBy('sale_id');
        $salesWithReturns = (clone $base)
            ->leftJoinSub($returnsBySale, 'approved_returns', fn ($join) => $join->on('sales.id', '=', 'approved_returns.sale_id'));
        $refundExpression = 'COALESCE(approved_returns.refund_total, 0)';
        $netTotalExpression = "CASE WHEN sales.total > {$refundExpression} THEN sales.total - {$refundExpression} ELSE 0 END";
        $netPaidExpression = "CASE WHEN sales.paid_total > {$refundExpression} THEN sales.paid_total - {$refundExpression} ELSE 0 END";
        $netOutstandingExpression = "CASE WHEN ({$netTotalExpression}) > ({$netPaidExpression}) THEN ({$netTotalExpression}) - ({$netPaidExpression}) ELSE 0 END";

        // Ringkasan dihitung dari SELURUH hasil filter, bukan hanya halaman aktif
        $row = (clone $salesWithReturns)->toBase()->selectRaw(
            "COUNT(*) as cnt,
             COALESCE(SUM({$netTotalExpression}), 0) as revenue,
             COALESCE(SUM({$netPaidExpression}), 0) as paid,
             COALESCE(SUM(sales.discount_total), 0) as discount,
             COALESCE(SUM({$netOutstandingExpression}), 0) as outstanding,
             COALESCE(SUM({$refundExpression}), 0) as refund_total,
             COALESCE(SUM(CASE WHEN sales.payment_status = ? AND ({$netTotalExpression}) > 0 THEN 1 ELSE 0 END), 0) as lunas",
            ['lunas']
        )->first();

        $summary = [
            'count' => (int) $row->cnt,
            'revenue' => (float) $row->revenue,
            'paid' => (float) $row->paid,
            'discount' => (float) $row->discount,
            'outstanding' => (float) $row->outstanding,
            'refund' => (float) $row->refund_total,
            'lunas' => (int) $row->lunas,
        ];

        $sales = (clone $salesWithReturns)
            ->select('sales.*', DB::raw("{$refundExpression} as refund_total"))
            ->with(['customer:id,name', 'cashier:id,name'])
            ->latest('sales.sold_at')
            ->latest('sales.id')
            ->paginate(12);

        $statusOptions = Sale::forStore($storeId)
            ->whereNotNull('payment_status')
            ->distinct()
            ->orderBy('payment_status')
            ->pluck('payment_status');

        return view('livewire.sales.history', [
            'sales' => $sales,
            'summary' => $summary,
            'statusOptions' => $statusOptions,
            'activeRange' => $this->activeRange(),
        ])->title('Riwayat Transaksi');
    }
}
