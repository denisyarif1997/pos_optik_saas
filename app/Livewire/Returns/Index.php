<?php

namespace App\Livewire\Returns;

use App\Models\ReturnModel;
use App\Models\Sale;
use App\Services\ReturnService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $code = '';

    public ?int $sale_id = null;

    public ?int $product_id = null;

    #[Validate('required|integer|min:1')]
    public int $qty = 1;

    public string $reason = '';

    // Filter datatable
    public string $search = '';

    public string $from = '';

    public string $to = '';

    public string $status = '';

    private const PRESETS = ['today', '7d', '30d', 'all'];

    public function mount(): void
    {
        $this->setRange('30d');
    }

    public function updated(string $name): void
    {
        if (in_array($name, ['search', 'from', 'to', 'status'], true)) {
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
        $this->status = '';
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

        return ReturnModel::forStore($storeId)
            ->with(['sale.customer', 'sale.items.product'])
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->whereHas('sale', fn ($s) => $s->where('code', 'like', $term));
            })
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($from, fn ($q) => $q->where('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn ($q) => $q->where('created_at', '<=', Carbon::parse($to)->endOfDay()));
    }

    public function findSale(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->with('items.product')->where('code', $this->code)->first();
        if (! $sale) {
            $this->addError('code', 'Nota tidak ditemukan diTokomu.');

            $this->sale_id = null;
            $this->product_id = null;
            $this->qty = 1;
            $this->reason = '';

            return;
        }

        $this->sale_id = $sale->id;
        $this->product_id = null;
        $this->qty = 1;
        $this->reason = '';
    }

    public function updatedProductId(): void
    {
        $this->qty = 1;
    }

    public function remainingQty(int $productId): int
    {
        if (! $this->sale_id) {
            return 0;
        }

        $storeId = app('activeStore')->id();

        $item = Sale::forStore($storeId)->with('items')->find($this->sale_id)
            ?->items->firstWhere('product_id', $productId);

        if (! $item) {
            return 0;
        }

        $already = (int) ReturnModel::forStore($storeId)
            ->where('sale_id', $this->sale_id)
            ->where('product_id', $productId)
            ->sum('qty');

        return max(0, (int) $item->qty - $already);
    }

    public function save(ReturnService $returns): void
    {
        $this->validate();
        $this->authorize('create', ReturnModel::class);

        if ($this->product_id && $this->qty > $this->remainingQty($this->product_id)) {
            $this->addError('qty', 'Melebihi sisa retur (maksimal '.$this->remainingQty($this->product_id).').');

            return;
        }

        $returns->create(auth()->user(), $this->sale_id, $this->product_id, $this->qty, $this->reason ?: null);
        session()->flash('success', 'Retur tersimpan, stok kembali bertambah.');
        $this->dispatch('notify', type: 'success', message: 'Retur tersimpan, stok kembali bertambah.');
        $this->reset(['sale_id', 'product_id', 'qty', 'reason']);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = $this->sale_id ? Sale::forStore($storeId)->with('items.product')->find($this->sale_id) : null;

        // Sisa qty yang masih dapat dikembalikan per produk (untuk batas input qty di form)
        $returnable = [];
        if ($sale) {
            foreach ($sale->items as $it) {
                $already = (int) ReturnModel::forStore($storeId)
                    ->where('sale_id', $sale->id)
                    ->where('product_id', $it->product_id)
                    ->sum('qty');
                $returnable[$it->product_id] = max(0, (int) $it->qty - $already);
            }
        }

        $base = $this->baseQuery($storeId);

        // Ringkasan dihitung dari SELURUH hasil filter, bukan hanya halaman aktif
        $row = (clone $base)->toBase()->selectRaw(
            'COUNT(*) as cnt,
             COALESCE(SUM(refund_amount), 0) as refund_total'
        )->first();

        $summary = [
            'count' => (int) $row->cnt,
            'refund' => (float) $row->refund_total,
        ];

        $returns = (clone $base)
            ->latest('created_at')
            ->latest('id')
            ->paginate(15);

        $statusOptions = ReturnModel::forStore($storeId)
            ->whereNotNull('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        return view('livewire.returns.index', [
            'sale' => $sale,
            'returns' => $returns,
            'summary' => $summary,
            'statusOptions' => $statusOptions,
            'activeRange' => $this->activeRange(),
            'returnable' => $returnable,
        ])->title('Retur Penjualan');
    }
}
