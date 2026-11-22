<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public string $search = '';

    public ?int $supplier_id = null;

    public string $productSearch = '';

    public array $cart = [];

    public function addProduct(int $id): void
    {
        foreach ($this->cart as &$row) {
            if ($row['product_id'] === $id) {
                $row['qty']++;

                return;
            }
        }
        $this->cart[] = ['product_id' => $id, 'qty' => 1, 'buy_price' => 0, 'sph' => null, 'cyl' => null, 'add_val' => 0.00];
    }

    public function removeRow(int $i): void
    {
        unset($this->cart[$i]);
        $this->cart = array_values($this->cart);
    }

    public function save(PurchaseService $purchases): void
    {
        $this->authorize('create', Purchase::class);
        $purchase = $purchases->create(auth()->user(), [
            'supplier_id' => $this->supplier_id,
            'items' => array_map(fn ($r) => [
                'product_id' => $r['product_id'],
                'qty' => $r['qty'],
                'buy_price' => $r['buy_price'],
                'sph' => $r['sph'],
                'cyl' => $r['cyl'],
                'add_val' => $r['add_val'],
            ], $this->cart),
        ]);

        session()->flash('success', 'Pembelian '.$purchase->code.' tersimpan, stok bertambah.');
        $this->reset(['cart', 'productSearch']);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $products = $this->productSearch
            ? Product::forStore($storeId)->where('is_active', true)->where('name', 'like', '%'.$this->productSearch.'%')->limit(8)->get()
            : collect();

        $detailed = [];
        $total = 0;
        foreach ($this->cart as $i => $row) {
            $p = Product::forStore($storeId)->find($row['product_id']);
            if ($p) {
                $buy = (float) ($row['buy_price'] ?: $p->cost_price);
                $total += $buy * $row['qty'];
                $detailed[] = ['index' => $i, 'product' => $p, 'qty' => $row['qty'], 'buy_price' => $row['buy_price']];
            }
        }

        return view('livewire.purchases.index', [
            'suppliers' => Supplier::forStore($storeId)->where('is_active', true)->orderBy('name')->get(),
            'products' => $products,
            'detailed' => $detailed,
            'total' => $total,
            'history' => Purchase::forStore($storeId)->latest()->limit(10)->get(),
        ])->title('Pembelian');
    }
}
