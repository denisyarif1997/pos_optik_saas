<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use Livewire\Component;

class StockHistory extends Component
{
    public $search = '';

    public $productId;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->productId = null;
    }

    public function getProductsProperty()
    {
        $query = Product::forActiveStore()->whereNotNull('stock');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            });
        }

        return $query->latest()->paginate(15);
    }

    public function render()
    {
        return view('livewire.inventory.stock-history', [
            'products' => $this->products,
        ]);
    }
}
