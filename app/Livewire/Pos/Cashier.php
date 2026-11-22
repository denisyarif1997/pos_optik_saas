<?php

namespace App\Livewire\Pos;

use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\LabOrder;
use App\Models\OpticalOrder;
use App\Models\OpticalOrderPayment;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockLensMatrix;
use App\Services\CheckoutTotals;
use App\Services\SaleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

    public string $discount_percent = '0';

    public bool $apply_ppn = false;

    public ?int $cash_account_id = null;

    public float $pay_amount = 0;

    public function mount(): void
    {
        $storeId = app('activeStore')->id();
        $this->cash_account_id = CashAccount::forStore($storeId)
            ->where('is_active', true)
            ->orderBy('id')
            ->value('id');
    }

    public function addToCart(int $productId): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $product = Product::forStore($storeId)->where('is_active', true)->findOrFail($productId);

        foreach ($this->cart as &$row) {
            if ($row['product_id'] === $product->id) {
                $row['qty']++;
                $this->dispatchTotalsToBrowser();

                return;
            }
        }

        $this->cart[] = [
            'product_id' => $product->id,
            'qty' => 1,
            'discount' => 0,
            'sph' => null,
            'cyl' => null,
            'add_val' => 0.00,
            'item_type' => $this->mode === 'optical' ? 'optical' : 'sale',
        ];
        $this->dispatchTotalsToBrowser();
    }

    // Fungsi baru untuk tombol + di keranjang
    public function incrementQty(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['qty']++;
            $this->dispatchTotalsToBrowser();
        }
    }

    // Fungsi baru untuk tombol - di keranjang
    public function decrementQty(int $index): void
    {
        if (isset($this->cart[$index])) {
            if ($this->cart[$index]['qty'] > 1) {
                $this->cart[$index]['qty']--;
                $this->dispatchTotalsToBrowser();
            } else {
                $this->removeRow($index);
            }
        }
    }

    public function removeRow(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->dispatchTotalsToBrowser();
    }

    public function updateLensSpecs(int $index, string $sph, string $cyl, string $add): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        $this->cart[$index]['sph'] = $sph === '' ? null : (float) $sph;
        $this->cart[$index]['cyl'] = $cyl === '' ? null : (float) $cyl;
        $this->cart[$index]['add_val'] = $add === '' ? 0.00 : (float) $add;

        $this->dispatchTotalsToBrowser();
    }

    public function updatedDiscountPercent(): void
    {
        $this->dispatchTotalsToBrowser();
    }

    public function updatedCart(): void
    {
        $this->dispatchTotalsToBrowser();
    }

    public function updatedApplyPpn(): void
    {
        $this->dispatchTotalsToBrowser();
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
        $itemDiscount = 0;

        if ($storeId) {
            foreach ($this->cart as $row) {
                $product = Product::forStore($storeId)->find($row['product_id']);
                if (! $product) {
                    continue;
                }

                $subtotal += ((float) $product->sell_price) * (int) $row['qty'];
                $itemDiscount += (float) ($row['discount'] ?? 0);
            }
        }

        return CheckoutTotals::calculate(
            $subtotal,
            $itemDiscount,
            (float) $this->discount_percent,
            $this->apply_ppn ? 11 : 0,
        );
    }

    public function checkout(SaleService $sales): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->validate([
            'customer_id' => [
                Rule::requiredIf($this->mode === 'optical'),
                'nullable',
                'integer',
                Rule::exists('customers', 'id')->where('store_id', $storeId),
            ],
            'mode' => 'required|in:sale,optical',
            'discount_percent' => 'required|numeric|between:0,100',
            'apply_ppn' => 'boolean',
            'pay_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer,qris,card',
            'cash_account_id' => [
                Rule::requiredIf($this->pay_amount > 0),
                'nullable',
                'integer',
                Rule::exists('cash_accounts', 'id')
                    ->where(fn ($query) => $query->where('store_id', $storeId)->where('is_active', true)),
            ],
        ]);

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
                'items' => array_map(fn ($r) => [
                    'product_id' => $r['product_id'],
                    'qty' => $r['qty'],
                    'discount' => $r['discount'] ?? 0,
                    'sph' => $r['sph'] ?? null,
                    'cyl' => $r['cyl'] ?? null,
                    'add_val' => $r['add_val'] ?? 0.00,
                ], $this->cart),
                'discount_percent' => (float) $this->discount_percent,
                'apply_ppn' => $this->apply_ppn,
                'payments' => $this->pay_amount > 0 ? [[
                    'method' => $this->payment_method,
                    'amount' => $this->pay_amount,
                    'cash_account_id' => $this->cash_account_id,
                ]] : [],
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
        $discountPercent = (float) $this->discount_percent;
        $ppnRate = $this->apply_ppn ? 11.0 : 0.0;
        $paymentMethod = $this->payment_method;
        $payAmount = $this->pay_amount;
        $cashAccountId = $this->cash_account_id;

        DB::transaction(function () use ($storeId, $customer, $code, $discountPercent, $ppnRate, $paymentMethod, $payAmount, $cashAccountId) {
            $snapshot = $this->prescription_id
                ? Prescription::forStore($storeId)->find($this->prescription_id)->only(['od_sph', 'od_cyl', 'od_axis', 'od_add', 'os_sph', 'os_cyl', 'os_axis', 'os_add', 'pd'])
                : [];

            $subtotal = 0;
            foreach ($this->cart as $row) {
                $product = Product::forStore($storeId)->lockForUpdate()->findOrFail((int) $row['product_id']);
                $subtotal += (float) $product->sell_price * max(1, (int) $row['qty']);
            }
            $totals = CheckoutTotals::calculate($subtotal, 0, $discountPercent, $ppnRate);

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
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount_total'],
                'discount_percent' => $discountPercent,
                'ppn_rate' => $ppnRate,
                'ppn_amount' => $totals['ppn_amount'],
                'total' => $totals['total'],
                'dp_total' => 0,
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

            $initialPayment = min($payAmount, $totals['total']);
            if ($initialPayment > 0) {
                OpticalOrderPayment::create([
                    'store_id' => $storeId,
                    'optical_order_id' => $order->id,
                    'received_by' => auth()->id(),
                    'cash_account_id' => $cashAccountId,
                    'paid_at' => now(),
                    'method' => $paymentMethod,
                    'amount' => $initialPayment,
                ]);
                $order->update(['dp_total' => $initialPayment]);
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

    private function dispatchTotalsToBrowser(): void
    {
        $this->dispatch('cashier-total-updated', total: (int) $this->totals['total']);
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
            'cashAccounts' => CashAccount::forStore($storeId)->where('is_active', true)->orderBy('name')->get(),
        ])->title('Kasir');
    }
}
