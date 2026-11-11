<?php

namespace App\Services;

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

        $items = $input['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages(['items' => 'Keranjang masih kosong.']);
        }

        return DB::transaction(function () use ($user, $storeId, $input, $items) {
            $subtotal = 0;
            $discountTotal = 0;
            $lines = [];

            foreach ($items as $row) {
                $product = Product::forStore($storeId)->lockForUpdate()->findOrFail((int) ($row['product_id'] ?? 0));
                $qty = max(1, (int) ($row['qty'] ?? 1));
                $discount = max(0, (float) ($row['discount'] ?? 0));

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages(['items' => 'Stok '.$product->name.' tidak cukup (sisa '.$product->stock.').']);
                }

                $price = (float) $product->sell_price;
                $lineSubtotal = ($price * $qty) - $discount;
                if ($lineSubtotal < 0) {
                    $lineSubtotal = 0;
                }

                $subtotal += $price * $qty;
                $discountTotal += $discount;
                $lines[] = compact('product', 'qty', 'price', 'discount', 'lineSubtotal');
            }

            $total = max(0, $subtotal - $discountTotal);
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
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'total' => $total,
                'paid_total' => $paidTotal,
                'payment_status' => $paidTotal >= $total ? 'lunas' : ($paidTotal > 0 ? 'dp' : 'belum_bayar'),
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
                Payment::create([
                    'store_id' => $storeId,
                    'sale_id' => $sale->id,
                    'received_by' => $user->id,
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
