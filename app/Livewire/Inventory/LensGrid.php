<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\StockLensMatrix;
use App\Services\ActiveStore;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class LensGrid extends Component
{
    public int $productId;

    public array $sphRange = [-2.00, -1.50, -1.00, -0.50, 0.00, 0.50, 1.00];

    public array $cylRange = [0.00, -0.50, -1.00, -1.50, -2.00];

    public float $addVal = 0.00;

    public array $matrix = [];

    #[Validate('required|integer|min:0')]
    public int $pendingQty = 0;

    public string $editingKey = '';

    public function mount(int $id): void
    {
        $storeId = app(ActiveStore::class)->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->findOrFail($id);
        $this->authorize('manage', StockLensMatrix::class);
        $this->productId = $id;

        $this->loadMatrix();
    }

    public function loadMatrix(): void
    {
        $storeId = app(ActiveStore::class)->id();

        $rows = StockLensMatrix::forStore($storeId)
            ->where('product_id', $this->productId)
            ->where('add_val', $this->addVal)
            ->get()
            ->keyBy(fn ($m) => $this->matrixKey($m->sph, $m->cyl));

        $this->matrix = [];
        foreach ($this->sphRange as $sph) {
            foreach ($this->cylRange as $cyl) {
                $key = $this->matrixKey($sph, $cyl);
                $this->matrix[$key] = [
                    'sph' => $sph,
                    'cyl' => $cyl,
                    'stock_qty' => $rows->has($key) ? (int) $rows[$key]->stock_qty : 0,
                    'id' => $rows->has($key) ? $rows[$key]->id : null,
                    'min_stock_alert' => $rows->has($key) ? (int) $rows[$key]->min_stock_alert : 2,
                ];
            }
        }
    }

    public function startEditing(string $key): void
    {
        $this->editingKey = $key;
        $this->pendingQty = (int) ($this->matrix[$key]['stock_qty'] ?? 0);
    }

    public function cancelEditing(): void
    {
        $this->editingKey = '';
        $this->pendingQty = 0;
    }

    public function saveStock(string $key): void
    {
        $this->validate();

        $storeId = app(ActiveStore::class)->id();
        abort_unless($storeId, 403);

        [$sph, $cyl] = $this->parseKey($key);
        $row = $this->matrix[$key] ?? null;
        if (! $row) {
            session()->flash('error', 'Sel matriks tidak ditemukan.');
            $this->dispatch('notify', type: 'error', message: 'Sel matriks tidak ditemukan.');
            $this->cancelEditing();

            return;
        }

        DB::transaction(function () use ($storeId, $sph, $cyl, $row) {
            if ($row['id']) {
                StockLensMatrix::forStore($storeId)->where('id', $row['id'])->update([
                    'stock_qty' => $this->pendingQty,
                ]);
            } else {
                StockLensMatrix::create([
                    'store_id' => $storeId,
                    'product_id' => $this->productId,
                    'sph' => $sph,
                    'cyl' => $cyl,
                    'add_val' => $this->addVal,
                    'stock_qty' => $this->pendingQty,
                    'min_stock_alert' => $row['min_stock_alert'] ?? 2,
                ]);
            }
        });

        $this->loadMatrix();
        $this->cancelEditing();
        session()->flash('success', 'Stok lensa diperbarui.');
        $this->dispatch('notify', type: 'success', message: 'Stok lensa diperbarui.');
    }

    public function isLowStock(array $cell): bool
    {
        return (int) $cell['stock_qty'] <= (int) ($cell['min_stock_alert'] ?? 2);
    }

    public function isOutOfStock(array $cell): bool
    {
        return (int) $cell['stock_qty'] === 0;
    }

    public function isEditing(string $key): bool
    {
        return $this->editingKey === $key;
    }

    private function matrixKey(float $sph, float $cyl): string
    {
        return number_format($sph, 2).'|'.number_format($cyl, 2);
    }

    private function parseKey(string $key): array
    {
        [$sph, $cyl] = explode('|', $key, 2);

        return [(float) $sph, (float) $cyl];
    }

    public function render()
    {
        $storeId = app(ActiveStore::class)->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->findOrFail($this->productId);

        return view('livewire.inventory.lens-grid', [
            'product' => $product,
            'lowCount' => collect($this->matrix)->filter(fn ($c) => $this->isLowStock($c))->count(),
            'outCount' => collect($this->matrix)->filter(fn ($c) => $this->isOutOfStock($c))->count(),
        ])->title('Grid Stok Lensa');
    }
}
