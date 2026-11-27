<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class StockHistory extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $from = '';

    public string $to = '';

    public ?int $productId = null;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');
    }

    public function updated(string $name): void
    {
        if (in_array($name, ['search', 'type', 'from', 'to', 'productId'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->type = '';
        $this->productId = null;
        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');
        $this->resetPage();
    }

    private function baseQuery(int $storeId): Builder
    {
        $from = $this->from;
        $to = $this->to;

        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
        }

        return StockMovement::forStore($storeId)
            ->when($this->productId, fn (Builder $q) => $q->where('product_id', $this->productId))
            ->when($this->type !== '', fn (Builder $q) => $q->where('type', $this->type))
            ->when($from !== '', fn (Builder $q) => $q->where('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to !== '', fn (Builder $q) => $q->where('created_at', '<=', Carbon::parse($to)->endOfDay()))
            ->when(trim($this->search) !== '', function (Builder $q): void {
                $term = '%'.trim($this->search).'%';
                $q->where(function (Builder $sub) use ($term): void {
                    $sub->where('notes', 'like', $term)
                        ->orWhereHas('product', fn (Builder $p) => $p->where('name', 'like', $term)->orWhere('sku', 'like', $term))
                        ->orWhereHas('creator', fn (Builder $u) => $u->where('name', 'like', $term));
                });
            });
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $base = $this->baseQuery($storeId);

        $row = (clone $base)->toBase()->selectRaw(
            'COUNT(*) as total_count,
             COALESCE(SUM(CASE WHEN qty > 0 THEN qty ELSE 0 END), 0) as total_in,
             COALESCE(SUM(CASE WHEN qty < 0 THEN ABS(qty) ELSE 0 END), 0) as total_out,
             COALESCE(SUM(qty), 0) as net_qty'
        )->first();

        $summary = [
            'count' => (int) $row->total_count,
            'in' => (int) $row->total_in,
            'out' => (int) $row->total_out,
            'net' => (int) $row->net_qty,
        ];

        $movements = (clone $base)
            ->with(['product:id,name,sku,type', 'creator:id,name'])
            ->latest('created_at')
            ->latest('id')
            ->paginate(20);

        $typeOptions = StockMovement::forStore($storeId)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        $products = Product::forStore($storeId)
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        return view('livewire.inventory.stock-history', [
            'movements' => $movements,
            'summary' => $summary,
            'typeOptions' => $typeOptions,
            'products' => $products,
        ])->title('Laporan Histori Stok');
    }
}
