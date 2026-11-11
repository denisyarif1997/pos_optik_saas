<?php

namespace App\Livewire\Pos;

use App\Models\Customer;
use App\Models\LabOrder;
use App\Models\OpticalOrder;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockLensMatrix;
use App\Services\SaleService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Cashier extends Component
{
    public string $search = '';

    public ?int $customer_id = null;

    public string $mode = 'sale';

    public array $cart = [];

    public ?int $prescription_id = null;

    public string $lab_name = '';

    public ?string $promised_date = null;

    public string $payment_method = 'cash';

    public float $pay_amount = 0;

    public function addToCart(int $productId): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->where('is_active', true)->findOrFail($productId);

        foreach ($this->cart as &$row) {
            if ($row['product_id'] === $product->id) {
                $row['qty']++;

                return;
            }
        }

        $this->cart[] = [
            'product_id' => $product->id,
            'qty' => 1,
            'discount' => 0,
            'item_type' => $this->mode === 'optical' ? 'optical' : 'sale',
        ];
    }

    // Fungsi baru untuk tombol + di keranjang
    public function incrementQty(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['qty']++;
        }
    }

    // Fungsi baru untuk tombol - di keranjang
    public function decrementQty(int $index): void
    {
        if (isset($this->cart[$index])) {
            if ($this->cart[$index]['qty'] > 1) {
                $this->cart[$index]['qty']--;
            } else {
                $this->removeRow($index);
            }
        }
    }

    public function removeRow(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function updatedMode(): void
    {
        foreach ($this->cart as $i => &$row) {
            $row['item_type'] = $this->mode === 'optical' ? 'optical' : 'sale';
        }
    }

    public function getTotalsProperty(): array
    {
        $storeId = app('activeStore')->id();
        $subtotal = 0;
        $discount = 0;

        if ($storeId) {
            foreach ($this->cart as $row) {
                $product = Product::forStore($storeId)->find($row['product_id']);
                if (! $product) {
                    continue;
                }$subtotal += ((float) $product->sell_price) * (int) $row['qty'];
                $discount += (float) ($row['discount'] ?? 0);
            }
        }

        return ['subtotal' => $subtotal, 'discount' => $discount, 'total' => max(0, $subtotal - $discount)];
    }

    public function checkout(SaleService $sales): void
    {
        $this->validate([
            'customer_id' => 'nullable|integer',
            'mode' => 'required|in:sale,optical',
        ]);

        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang masih kosong.');

            return;
        }

        $customer = null;
        if ($this->customer_id) {
            $customer = Customer::forStore($storeId)->findOrFail($this->customer_id);
        }

        if ($this->mode === 'sale') {
            $this->authorize('create', Sale::class);

            $sale = $sales->create(auth()->user(), [
                'customer_id' => $this->customer_id,
                'items' => array_map(fn ($r) => ['product_id' => $r['product_id'], 'qty' => $r['qty'], 'discount' => $r['discount']], $this->cart),
                'payments' => $this->pay_amount > 0 ? [['method' => $this->payment_method, 'amount' => $this->pay_amount]] : [],
            ]);

            session()->flash('success', 'Transaksi '.$sale->code.' tersimpan.');
            $this->redirectRoute('sales.show', ['id' => $sale->id], navigate: true);

            return;
        }

        $this->authorize('create', OpticalOrder::class);

        if ($this->prescription_id && $customer) {
            Prescription::forStore($storeId)->where('customer_id', $customer->id)->findOrFail($this->prescription_id);
        }

        $nextNumber = (int) OpticalOrder::forStore($storeId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->value('id');
        $code = 'OPT-'.now()->format('Ymd').'-'.str_pad((string) ($nextNumber > 0 ? $nextNumber % 10000 : 0), 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($storeId, $customer, $code) {
            $snapshot = $this->prescription_id
                ? Prescription::forStore($storeId)->find($this->prescription_id)->only(['od_sph', 'od_cyl', 'od_axis', 'od_add', 'os_sph', 'os_cyl', 'os_axis', 'os_add', 'pd'])
                : [];

            $order = OpticalOrder::create([
                'store_id' => $storeId,
                'customer_id' => $customer ? $customer->id : null,
                'prescription_id' => $this->prescription_id,
                'order_number' => $code,
                'code' => $code,
                'lab_name' => $this->lab_name ?: null,
                'prescription_snapshot' => $snapshot,
                'ordered_at' => now()->toDateString(),
                'promised_date' => $this->promised_date ?: null,
                'total' => (float) $this->totals['total'],
                'dp_total' => (float) $this->pay_amount,
                'status' => 'DRAFT',
            ]);

            foreach ($this->cart as $row) {
                $product = Product::forStore($storeId)->find($row['product_id']);
                if (! $product) {
                    continue;
                }

                $order->items()->create([
                    'store_id' => $storeId,
                    'optical_order_id' => $order->id,
                    'product_id' => $product->id,
                    'item_type' => $row['item_type'] ?? 'optical',
                    'description' => $product->name,
                    'qty' => (int) $row['qty'],
                    'price' => (float) $product->sell_price,
                    'subtotal' => ((float) $product->sell_price) * (int) $row['qty'],
                ]);

                $qty = (int) $row['qty'];
                if ($product->type === 'STOCK_LENS') {
                    if ($this->prescription_id) {
                        $this->deductStockLensMatrix($product->id, $snapshot);
                    } else {
                        $product->decrement('stock', $qty);
                    }
                } elseif ($product->type === 'FRAME') {
                    $product->decrement('stock', $qty);
                }
            }

            if ($this->lab_name) {
                LabOrder::create([
                    'store_id' => $storeId,
                    'order_id' => $order->id,
                    'lab_supplier_name' => $this->lab_name,
                    'status' => 'MENUNGGU',
                    'lab_cost' => 0,
                    'expected_completion_date' => $this->promised_date ?: null,
                ]);

                $order->update(['status' => 'PESANAN']);
            }
        });

        session()->flash('success', 'Pesanan optik '.$code.' dibuat.');
        $this->redirectRoute('optical-orders.show', ['id' => OpticalOrder::forStore($storeId)->where('order_number', $code)->first()->id], navigate: true);
    }

    protected function deductStockLensMatrix(int $productId, array $snapshot): void
    {
        $storeId = app('activeStore')->id();

        foreach (['od', 'os'] as $eye) {
            $sph = $snapshot[$eye.'_sph'] ?? null;
            $cyl = $snapshot[$eye.'_cyl'] ?? null;
            if ($sph === null || $cyl === null) {
                continue;
            }

            $matrix = StockLensMatrix::forStore($storeId)
                ->where('product_id', $productId)
                ->where('sph', (float) $sph)
                ->where('cyl', (float) $cyl)
                ->where('add_val', (float) ($snapshot[$eye.'_add'] ?? 0.00))
                ->where('stock_qty', '>', 0)
                ->lockForUpdate()
                ->first();

            if ($matrix) {
                $matrix->decrement('stock_qty', 1);
            }
        }
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->authorize('create', Sale::class);
        $this->authorize('create', OpticalOrder::class);

        $search = trim($this->search);

        $results = $search !== ''
            ? Product::forStore($storeId)->where('is_active', true)
                ->where(fn ($q) => $q
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('barcode', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%'))
                ->limit(12)->get()
            : collect();

        $cartDetailed = [];
        foreach ($this->cart as $i => $row) {
            $product = Product::forStore($storeId)->find($row['product_id']);
            if ($product) {
                $cartDetailed[] = ['index' => $i, 'product' => $product, 'qty' => $row['qty'], 'discount' => $row['discount'] ?? 0, 'item_type' => $row['item_type'] ?? 'sale'];
            }
        }

        $prescriptions = collect();
        if ($this->customer_id) {
            $customer = Customer::forStore($storeId)->find($this->customer_id);
            $prescriptions = $customer ? $customer->prescriptions()->orderBy('checked_at', 'desc')->limit(50)->get() : collect();
        }

        return view('livewire.pos.cashier', [
            'results' => $results,
            'cartDetailed' => $cartDetailed,
            'totals' => $this->totals,
            'customers' => Customer::forStore($storeId)->where('is_active', true)->orderBy('name')->limit(100)->get(),
            'prescriptions' => $prescriptions,
        ])->title('Kasir');
    }
}
