<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->findOrFail($id);
        $this->authorize('delete', $product);
        $product->update(['is_active' => false]);

        session()->flash('success', 'Produk dinonaktifkan.');
        $this->dispatch('notify', type: 'success', message: 'Produk dinonaktifkan.');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $products = Product::forStore($storeId)
            ->with('category')
            ->when($this->search, fn ($q) => $q->where(function ($w) {
                $w->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')
                    ->orWhere('barcode', 'like', '%'.$this->search.'%');
            }))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->latest()
            ->paginate(12);

        return view('livewire.products.index', [
            'products' => $products,
            'lowCount' => Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count(),
        ])->title('Produk & Stok');
    }
}
