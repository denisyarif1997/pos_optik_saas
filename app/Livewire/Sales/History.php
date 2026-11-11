<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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

        // Ringkasan dihitung dari SELURUH hasil filter, bukan hanya halaman aktif
        $row = (clone $base)->toBase()->selectRaw(
            'COUNT(*) as cnt,
             COALESCE(SUM(total), 0) as revenue,
             COALESCE(SUM(paid_total), 0) as paid,
             COALESCE(SUM(discount_total), 0) as discount,
             COALESCE(SUM(CASE WHEN total > paid_total THEN total - paid_total ELSE 0 END), 0) as outstanding,
             COALESCE(SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END), 0) as lunas',
            ['lunas']
        )->first();

        $summary = [
            'count' => (int) $row->cnt,
            'revenue' => (float) $row->revenue,
            'paid' => (float) $row->paid,
            'discount' => (float) $row->discount,
            'outstanding' => (float) $row->outstanding,
            'lunas' => (int) $row->lunas,
        ];

        $sales = (clone $base)
            ->with(['customer:id,name', 'cashier:id,name'])
            ->latest('sold_at')
            ->latest('id')
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