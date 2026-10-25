<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $products = Product::forStore($storeId)
            ->where('type', 'STOCK_LENS')
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('sku', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(12);

        return view('livewire.inventory.index', [
            'products' => $products,
        ])->title('Inventori Lensa Stok');
    }
}
