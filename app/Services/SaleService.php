<?php

namespace App\Services;

use App\Models\CashAccount;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockLensMatrix;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function nextSequenceNumber(int $storeId): int
    {
        $date = now()->format('Ymd');
        $prefix = 'JUAL-'.$date.'-';

        $sequence = ((int) Sale::forStore($storeId)
            ->whereDate('sold_at', today())
            ->max('sequence_number')) + 1;

        // Pastikan kode transaksi hasil generate tidak bentrok dengan data yang sudah ada.
        while (Sale::forStore($storeId)
            ->where('code', $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT))
            ->exists()) {
            $sequence++;
        }

        return $sequence;
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
        $discountAmount = $input['discount_amount'] ?? 0;
        if (! is_numeric($discountAmount) || (float) $discountAmount < 0) {
            throw ValidationException::withMessages(['discount_amount' => 'Diskon transaksi tidak boleh negatif.']);
        }

        $discountAmount = (float) $discountAmount;
        $ppnRate = ! empty($input['apply_ppn']) ? 11.0 : 0.0;
        $items = $input['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages(['items' => 'Keranjang masih kosong.']);
        }

        return DB::transaction(function () use ($user, $storeId, $input, $items, $discountPercent, $discountAmount, $ppnRate) {
            $lines = [];

            foreach ($items as $index => $row) {
                $product = Product::forStore($storeId)->lockForUpdate()->findOrFail((int) ($row['product_id'] ?? 0));
                $qty = max(1, (int) ($row['qty'] ?? 1));

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages(['items' => 'Stok '.$product->name.' tidak cukup (sisa '.$product->stock.').']);
                }

                $lines[$index] = [
                    'product' => $product,
                    'qty' => $qty,
                    'price' => (float) $product->sell_price,
                    'gross' => (float) $product->sell_price * $qty,
                    'discount' => max(0, (float) ($row['discount'] ?? 0)),
                    'ppn_rate' => $this->itemPpnRate($row, $ppnRate),
                    'sph' => $row['sph'] ?? null,
                    'cyl' => $row['cyl'] ?? null,
                    'add_val' => $row['add_val'] ?? 0.00,
                ];
            }

            $totals = CheckoutTotals::calculateForLines($lines, $discountPercent, $discountAmount);
            $payments = $input['payments'] ?? [];
            $paidTotal = 0;
            foreach ($payments as $p) {
                $paidTotal += max(0, (float) ($p['amount'] ?? 0));
            }

            $sequence = $this->nextSequenceNumber($storeId);
            $code = 'JUAL-'.now()->format('Ymd').'-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'store_id' => $storeId,
                'customer_id' => $input['customer_id'] ?? null,
                'cashier_id' => $user->id,
                'code' => $code,
                'sequence_number' => $sequence,
                'sold_at' => now(),
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount_total'],
                'discount_percent' => $discountPercent,
                'discount_amount' => $totals['discount_amount'],
                'ppn_rate' => $totals['ppn_rate'],
                'ppn_amount' => $totals['ppn_amount'],
                'total' => $totals['total'],
                'paid_total' => $paidTotal,
                'payment_status' => $paidTotal >= $totals['total'] ? 'lunas' : ($paidTotal > 0 ? 'dp' : 'belum_bayar'),
                'status' => 'completed',
                'notes' => $input['notes'] ?? null,
            ]);

            foreach ($lines as $index => $line) {
                $lineTotals = $totals['lines'][$index];

                $sale->items()->create([
                    'store_id' => $storeId,
                    'product_id' => $line['product']->id,
                    'sph' => $line['sph'],
                    'cyl' => $line['cyl'],
                    'add_val' => $line['add_val'],
                    'qty' => $line['qty'],
                    'price' => $line['price'],
                    'discount' => $lineTotals['discount'],
                    'ppn_rate' => $lineTotals['ppn_rate'],
                    'ppn_amount' => $lineTotals['ppn_amount'],
                    'subtotal' => $lineTotals['subtotal'],
                ]);

                $before = (int) $line['product']->stock;
                $line['product']->decrement('stock', $line['qty']);

                // Decrement StockLensMatrix for lens items with SPH/CYL/ADD specs
                if ($line['sph'] !== null && $line['cyl'] !== null) {
                    $matrix = StockLensMatrix::forStore($storeId)
                        ->where('product_id', $line['product']->id)
                        ->where('sph', (float) $line['sph'])
                        ->where('cyl', (float) $line['cyl'])
                        ->where('add_val', (float) $line['add_val'])
                        ->where('stock_qty', '>', 0)
                        ->lockForUpdate()
                        ->first();

                    if ($matrix) {
                        $matrix->decrement('stock_qty', $line['qty']);
                    }
                }

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

    /**
     * Effective PPN rate for a single line: the per item checkbox taxes that item,
     * while the checkout wide "apply PPN" flag taxes every line.
     *
     * @param  array<string, mixed>  $row
     */
    private function itemPpnRate(array $row, float $checkoutPpnRate): float
    {
        return ! empty($row['ppn']) || $checkoutPpnRate > 0 ? 11.0 : 0.0;
    }
}
