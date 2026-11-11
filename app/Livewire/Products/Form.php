<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $productId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('required|string|in:FRAME,STOCK_LENS,RX_LENS,CONTACT_LENS,SERVICE')]
    public string $type = 'frame';

    #[Validate('nullable|integer|exists:categories,id')]
    public ?int $category_id = null;

    #[Validate('nullable|string|max:60')]
    public ?string $sku = '';

    #[Validate('nullable|string|max:60')]
    public ?string $barcode = '';

    #[Validate('required|numeric|min:0')]
    public float $sell_price = 0;

    #[Validate('required|numeric|min:0')]
    public float $cost_price = 0;

    #[Validate('required|integer|min:0')]
    public int $stock = 0;

    #[Validate('required|integer|min:0')]
    public int $min_stock = 0;

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $product = Product::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $product);
            $this->productId = $product->id;
            $this->fill($product->only([
                'name', 'type', 'category_id', 'sku', 'barcode',
                'sell_price', 'cost_price', 'stock', 'min_stock',
            ]));
        } else {
            $this->authorize('create', Product::class);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'category_id' => $this->category_id && Category::forStore($storeId)->where('id', $this->category_id)->exists() ? $this->category_id : null,
            'sku' => $this->sku ?: null,
            'barcode' => $this->barcode ?: null,
            'sell_price' => $this->sell_price,
            'cost_price' => $this->cost_price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'is_active' => true,
        ];

        if ($this->productId) {
            $product = Product::forStore($storeId)->findOrFail($this->productId);
            $this->authorize('update', $product);
            unset($data['stock']);
            $product->update($data);
        } else {
            $this->authorize('create', Product::class);
            $data['store_id'] = $storeId;
            Product::create($data);
        }

        $this->redirectRoute('products.index', navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.products.form', [
            'categories' => Category::forStore($storeId)->where('is_active', true)->orderBy('name')->get(),
        ])->title($this->productId ? 'Ubah Produk' : 'Tambah Produk');
    }
}
