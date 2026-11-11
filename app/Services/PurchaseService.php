<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    public function create(User $user, array $input): Purchase
    {
        $storeId = app('activeStore')->id($user);
        abort_unless($storeId, 403);

        $items = $input['items'] ?? [];
        if (empty($items)) {
            throw ValidationException::withMessages(['items' => 'Item pembelian kosong.']);
        }

        return DB::transaction(function () use ($user, $storeId, $input, $items) {
            $total = 0;
            $lines = [];

            foreach ($items as $row) {
                $product = Product::forStore($storeId)->lockForUpdate()->findOrFail((int) ($row['product_id'] ?? 0));
                $qty = max(1, (int) ($row['qty'] ?? 1));
                $buy = max(0, (float) ($row['buy_price'] ?? $product->cost_price));
                $subtotal = $buy * $qty;
                $total += $subtotal;
                $lines[] = compact('product', 'qty', 'buy', 'subtotal');
            }

            $purchase = Purchase::create([
                'store_id' => $storeId,
                'supplier_id' => $input['supplier_id'] ?? null,
                'code' => 'PO-'.now()->format('Ymd').'-'.str_pad((string) ($this->getNextNumber($storeId) + 1), 4, '0', STR_PAD_LEFT),
                'purchased_at' => $input['purchased_at'] ?? now()->toDateString(),
                'total' => $total,
                'status' => 'received',
                'notes' => $input['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                $purchase->items()->create([
                    'store_id' => $storeId,
                    'product_id' => $line['product']->id,
                    'qty' => $line['qty'],
                    'buy_price' => $line['buy'],
                    'subtotal' => $line['subtotal'],
                ]);

                $before = (int) $line['product']->stock;
                $line['product']->increment('stock', $line['qty']);
                if ((float) $line['buy'] > 0) {
                    $line['product']->update(['cost_price' => $line['buy']]);
                }

                StockMovement::create([
                    'store_id' => $storeId,
                    'product_id' => $line['product']->id,
                    'type' => 'in',
                    'qty' => $line['qty'],
                    'stock_before' => $before,
                    'stock_after' => $before + $line['qty'],
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'created_by' => $user->id,
                    'notes' => 'Pembelian '.$purchase->code,
                ]);
            }

            return $purchase->fresh('items');
        });
    }

    private function getNextNumber(int $storeId): int
    {
        $id = Purchase::forStore($storeId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->value('id');

        return $id > 0 ? (int) substr((string) $id, -4) : 0;
    }
}
