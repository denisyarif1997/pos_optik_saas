<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function adjust(User $user, int $productId, int $qty, string $type, ?string $notes = null): Product
    {
        $storeId = app('activeStore')->id($user);
        abort_unless($storeId, 403);

        return DB::transaction(function () use ($user, $storeId, $productId, $qty, $type, $notes) {
            $product = Product::forStore($storeId)->lockForUpdate()->findOrFail($productId);

            if ($qty === 0) {
                throw ValidationException::withMessages(['qty' => 'Jumlah tidak boleh nol.']);
            }

            $before = (int) $product->stock;
            $after = $before + $qty;

            if ($after < 0) {
                throw ValidationException::withMessages(['qty' => 'Stok tidak mencukupi. Sisa: '.$before]);
            }

            $product->update(['stock' => $after]);

            StockMovement::create([
                'store_id' => $storeId,
                'product_id' => $product->id,
                'type' => $type,
                'qty' => $qty,
                'stock_before' => $before,
                'stock_after' => $after,
                'created_by' => $user->id,
                'notes' => $notes,
            ]);

            return $product->fresh();
        });
    }
}
