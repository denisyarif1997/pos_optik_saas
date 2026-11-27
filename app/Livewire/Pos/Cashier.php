<?php

namespace App\Livewire\Pos;

use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\Sale;
use App\Services\CheckoutTotals;
use App\Services\SaleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public string $payment_method = 'cash';

    public string $discount_amount = '0';

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
            'ppn' => $this->apply_ppn,
            'sph' => null,
            'cyl' => null,
            'add_val' => 0.00,
            'item_type' => 'sale',
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

    public function updatedDiscountAmount(): void
    {
        $this->dispatchTotalsToBrowser();
    }

    /**
     * Nested cart updates (per item discount, per item PPN, lens specs) recalculate the
     * totals. Item discounts are clamped to their own line total so the cart never goes
     * negative while the cashier is typing.
     */
    public function updatedCart(mixed $value = null, ?string $key = null): void
    {
        if (is_string($key) && str_ends_with($key, '.discount')) {
            $this->normalizeCartDiscounts();
        }

        if (is_string($key) && str_ends_with($key, '.ppn')) {
            $this->syncGlobalPpnFlag();
        }

        $this->dispatchTotalsToBrowser();
    }

    public function updatedApplyPpn(): void
    {
        foreach ($this->cart as $index => $row) {
            $this->cart[$index]['ppn'] = (bool) $this->apply_ppn;
        }

        $this->dispatchTotalsToBrowser();
    }

    public function getTotalsProperty(): array
    {
        $storeId = app('activeStore')->id();
        $lines = [];

        if ($storeId) {
            foreach ($this->cart as $row) {
                $product = Product::forStore($storeId)->find($row['product_id']);
                if (! $product) {
                    continue;
                }

                $lines[] = [
                    'gross' => ((float) $product->sell_price) * max(1, (int) $row['qty']),
                    'discount' => (float) ($row['discount'] ?? 0),
                    'ppn_rate' => $this->itemPpnRate($row),
                ];
            }
        }

        return CheckoutTotals::calculateForLines($lines, 0.0, (float) $this->discount_amount);
    }

    /**
     * PPN rate for a cart line: the per item checkbox taxes that item, while the
     * checkout wide PPN toggle taxes every line.
     *
     * @param  array<string, mixed>  $row
     */
    private function itemPpnRate(array $row): float
    {
        return ! empty($row['ppn']) || $this->apply_ppn ? 11.0 : 0.0;
    }

    /**
     * Gross amount (price × qty) of a cart line, or null when the product is gone.
     *
     * @param  array<string, mixed>  $row
     */
    private function lineGross(int $storeId, array $row): ?float
    {
        $product = Product::forStore($storeId)->find($row['product_id'] ?? 0);

        if (! $product) {
            return null;
        }

        return ((float) $product->sell_price) * max(1, (int) ($row['qty'] ?? 1));
    }

    private function normalizeCartDiscounts(): void
    {
        $storeId = app('activeStore')->id();

        if (! $storeId) {
            return;
        }

        foreach ($this->cart as $index => $row) {
            $gross = $this->lineGross($storeId, $row);

            if ($gross === null) {
                continue;
            }

            $this->cart[$index]['discount'] = round(min($gross, max(0.0, (float) ($row['discount'] ?? 0))), 2);
        }
    }

    /**
     * The checkout wide PPN toggle mirrors the per item checkboxes: it stays on only
     * while every line is taxed, so unchecking one item switches the bulk toggle off.
     */
    private function syncGlobalPpnFlag(): void
    {
        if ($this->cart === []) {
            $this->apply_ppn = false;

            return;
        }

        foreach ($this->cart as $row) {
            if (empty($row['ppn'])) {
                $this->apply_ppn = false;

                return;
            }
        }

        $this->apply_ppn = true;
    }

    /**
     * @throws ValidationException
     */
    private function validateCartLines(int $storeId): void
    {
        $errors = [];

        foreach ($this->cart as $index => $row) {
            $gross = $this->lineGross($storeId, $row);

            if ($gross === null) {
                $errors["cart.$index.discount"] = 'Produk pada baris ini sudah tidak tersedia.';

                continue;
            }

            $discount = (float) ($row['discount'] ?? 0);

            if ($discount < 0 || $discount > $gross) {
                $errors["cart.$index.discount"] = 'Diskon item tidak boleh melebihi harga baris (Rp '.number_format($gross, 0, ',', '.').').';
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Total of all lines after their own item discount: the ceiling for the
     * transaction level discount.
     */
    private function discountableSubtotal(int $storeId): float
    {
        $total = 0.0;

        foreach ($this->cart as $row) {
            $gross = $this->lineGross($storeId, $row);

            if ($gross === null) {
                continue;
            }

            $discount = min($gross, max(0.0, (float) ($row['discount'] ?? 0)));
            $total += $gross - $discount;
        }

        return round($total, 2);
    }

    /**
     * @throws ValidationException
     */
    private function validateTransactionDiscount(int $storeId): void
    {
        $discountableSubtotal = $this->discountableSubtotal($storeId);

        if ((float) $this->discount_amount > $discountableSubtotal) {
            throw ValidationException::withMessages([
                'discount_amount' => 'Diskon transaksi tidak boleh melebihi subtotal setelah diskon item (Rp '.number_format($discountableSubtotal, 0, ',', '.').').',
            ]);
        }
    }

    public function checkout(SaleService $sales): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->validate([
            'mode' => 'required|in:sale',
            'discount_amount' => 'required|numeric|min:0',
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
            $this->addError('cart', 'Keranjang masih kosong.');

            return;
        }

        $this->validateCartLines($storeId);
        $this->validateTransactionDiscount($storeId);

        $customer = null;
        if ($this->customer_id) {
            $customer = Customer::forStore($storeId)->findOrFail($this->customer_id);
        }

        if ($this->mode === 'sale') {
            $this->authorize('create', Sale::class);

            try {
                $sale = $sales->create(auth()->user(), [
                    'customer_id' => $this->customer_id,
                    'items' => array_map(fn ($r) => [
                        'product_id' => $r['product_id'],
                        'qty' => $r['qty'],
                        'discount' => $r['discount'] ?? 0,
                        'ppn' => (bool) ($r['ppn'] ?? false),
                        'sph' => $r['sph'] ?? null,
                        'cyl' => $r['cyl'] ?? null,
                        'add_val' => $r['add_val'] ?? 0.00,
                    ], $this->cart),
                    'discount_amount' => (float) $this->discount_amount,
                    'apply_ppn' => $this->apply_ppn,
                    'payments' => $this->pay_amount > 0 ? [[
                        'method' => $this->payment_method,
                        'amount' => $this->pay_amount,
                        'cash_account_id' => $this->cash_account_id,
                    ]] : [],
                ]);
            } catch (\Exception $e) {
                $this->addError('checkout', 'Transaksi gagal disimpan: '.$e->getMessage());

                return;
            }

            session()->flash('success', 'Transaksi '.$sale->code.' tersimpan.');
            $this->dispatch('notify', type: 'success', message: 'Transaksi '.$sale->code.' tersimpan.');
            $this->redirectRoute('sales.show', ['id' => $sale->id], navigate: true);

            return;
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
                $gross = ((float) $product->sell_price) * max(1, (int) ($row['qty'] ?? 1));
                $discount = min($gross, max(0.0, (float) ($row['discount'] ?? 0)));

                $cartDetailed[] = [
                    'index' => $i,
                    'product' => $product,
                    'qty' => $row['qty'],
                    'discount' => $discount,
                    'gross' => $gross,
                    'line_net' => round($gross - $discount, 2),
                    'ppn' => (bool) ($row['ppn'] ?? false),
                    'ppn_rate' => $this->itemPpnRate($row),
                    'item_type' => $row['item_type'] ?? 'sale',
                ];
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
