<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Services\StockService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Adjust extends Component
{
    public int $productId;

    #[Validate('required|integer|not_in:0')]
    public int $qty = 0;

    #[Validate('nullable|string|max:255')]
    public string $notes = '';

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->findOrFail($id);
        $this->authorize('update', $product);
        $this->productId = $product->id;
    }

    public function save(StockService $stocks): void
    {
        $this->validate();

        $stocks->adjust(auth()->user(), $this->productId, $this->qty, $this->qty > 0 ? 'in' : 'adjust', $this->notes ?: null);

        session()->flash('success', 'Stok diperbarui dan tercatat.');
        $this->dispatch('notify', type: 'success', message: 'Stok diperbarui dan tercatat.');
        $this->redirectRoute('products.index', navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.products.adjust', [
            'product' => Product::forStore($storeId)->findOrFail($this->productId),
        ])->title('Penyesuaian Stok');
    }
}
