<?php

namespace App\Services;

use App\Models\CashAccount;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function nextCode(int $storeId): string
    {
        $date = now()->format('Ymd');
        $nextNumber = $this->getNextNumber($storeId) + 1;

        return 'JUAL-'.$date.'-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function getNextNumber(int $storeId): int
    {
        $id = Sale::forStore($storeId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->value('id');

        return $id > 0 ? (int) substr((string) $id, -4) : 0;
    }

    public function create(User $user, array $input): Sale
    {
        $storeId = app('activeStore')->id($user);
        abort_unless($storeId, 403);

        $discountPercent = $input['discount_percent'] ?? 0;
        if (! is_numeric($discountPercent) || (float) $discountPercent < 0 || (float) $discountPercent > 100) {
            throw ValidationException::withMessages(['discount_percent' => 'Diskon harus antara 0 sampai 100 persen.']);
        }

        $discountPercent = (float) $discountPercent;
        $ppnRate = ! empty($input['apply_ppn']) ? 11.0 : 0.0;
        $items = $input['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages(['items' => 'Keranjang masih kosong.']);
        }

        return DB::transaction(function () use ($user, $storeId, $input, $items, $discountPercent, $ppnRate) {
            $subtotal = 0;
            $itemDiscountTotal = 0;
            $lines = [];

            foreach ($items as $row) {
                $product = Product::forStore($storeId)->lockForUpdate()->findOrFail((int) ($row['product_id'] ?? 0));
                $qty = max(1, (int) ($row['qty'] ?? 1));
                $gross = (float) $product->sell_price * $qty;
                $discount = min($gross, max(0, (float) ($row['discount'] ?? 0)));

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages(['items' => 'Stok '.$product->name.' tidak cukup (sisa '.$product->stock.').']);
                }

                $price = (float) $product->sell_price;
                $lineSubtotal = $gross - $discount;

                $subtotal += $gross;
                $itemDiscountTotal += $discount;
                $lines[] = compact('product', 'qty', 'price', 'discount', 'lineSubtotal');
            }

            $totals = CheckoutTotals::calculate($subtotal, $itemDiscountTotal, $discountPercent, $ppnRate);
            $payments = $input['payments'] ?? [];
            $paidTotal = 0;
            foreach ($payments as $p) {
                $paidTotal += max(0, (float) ($p['amount'] ?? 0));
            }

            $sale = Sale::create([
                'store_id' => $storeId,
                'customer_id' => $input['customer_id'] ?? null,
                'cashier_id' => $user->id,
                'code' => $this->nextCode($storeId),
                'sold_at' => now(),
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount_total'],
                'discount_percent' => $discountPercent,
                'ppn_rate' => $ppnRate,
                'ppn_amount' => $totals['ppn_amount'],
                'total' => $totals['total'],
                'paid_total' => $paidTotal,
                'payment_status' => $paidTotal >= $totals['total'] ? 'lunas' : ($paidTotal > 0 ? 'dp' : 'belum_bayar'),
                'status' => 'completed',
                'notes' => $input['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                $sale->items()->create([
                    'store_id' => $storeId,
                    'product_id' => $line['product']->id,
                    'qty' => $line['qty'],
                    'price' => $line['price'],
                    'discount' => $line['discount'],
                    'subtotal' => $line['lineSubtotal'],
                ]);

                $before = (int) $line['product']->stock;
                $line['product']->decrement('stock', $line['qty']);

                StockMovement::create([
                    'store_id' => $storeId,
                    'product_id' => $line['product']->id,
                    'type' => 'out',
                    'qty' => -$line['qty'],
                    'stock_before' => $before,
                    'stock_after' => $before - $line['qty'],
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'created_by' => $user->id,
                ]);
            }

            foreach ($payments as $p) {
                $amount = max(0, (float) ($p['amount'] ?? 0));
                if ($amount <= 0) {
                    continue;
                }

                $cashAccountId = (int) ($p['cash_account_id'] ?? 0);
                $cashAccount = $cashAccountId > 0
                    ? CashAccount::forStore($storeId)->where('is_active', true)->find($cashAccountId)
                    : null;

                if (! $cashAccount) {
                    throw ValidationException::withMessages(['cash_account_id' => 'Pilih akun kas aktif untuk pembayaran.']);
                }

                Payment::create([
                    'store_id' => $storeId,
                    'sale_id' => $sale->id,
                    'received_by' => $user->id,
                    'cash_account_id' => $cashAccount->id,
                    'paid_at' => now(),
                    'method' => $p['method'] ?? 'cash',
                    'amount' => $amount,
                    'notes' => $p['notes'] ?? null,
                ]);
            }

            return $sale->fresh(['items', 'payments']);
        });
    }
}
