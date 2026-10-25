<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnService
{
    public function create(User $user, int $saleId, int $productId, int $qty, ?string $reason = null): ReturnModel
    {
        $storeId = app('activeStore')->id($user);
        abort_unless($storeId, 403);

        return DB::transaction(function () use ($user, $storeId, $saleId, $productId, $qty, $reason) {
            $sale = Sale::forStore($storeId)->findOrFail($saleId);
            $item = $sale->items()->where('product_id', $productId)->first();
            if (! $item) {
                throw ValidationException::withMessages(['product_id' => 'Produk tidak ada di nota ini.']);
            }

            $already = ReturnModel::forStore($storeId)->where('sale_id', $sale->id)->where('product_id', $productId)->sum('qty');
            if ($already + $qty > $item->qty) {
                throw ValidationException::withMessages(['qty' => 'Melebihi qty pembelian (sisa retur: '.($item->qty - $already).').']);
            }

            $product = Product::forStore($storeId)->lockForUpdate()->findOrFail($productId);
            $before = (int) $product->stock;
            $product->increment('stock', $qty);

            StockMovement::create([
                'store_id' => $storeId,
                'product_id' => $product->id,
                'type' => 'in',
                'qty' => $qty,
                'stock_before' => $before,
                'stock_after' => $before + $qty,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'created_by' => $user->id,
                'notes' => 'Retur '.$sale->code,
            ]);

            return ReturnModel::create([
                'store_id' => $storeId,
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'created_by' => $user->id,
                'qty' => $qty,
                'refund_amount' => ((float) $item->price) * $qty,
                'reason' => $reason,
                'status' => 'approved',
            ]);
        });
    }
}
