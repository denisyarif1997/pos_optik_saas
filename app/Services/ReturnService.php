<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\StockLensMatrix;
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
            if ($qty < 1) {
                throw ValidationException::withMessages(['qty' => 'Jumlah retur minimal 1.']);
            }

            $sale = Sale::forStore($storeId)->lockForUpdate()->findOrFail($saleId);
            $item = $sale->items()->where('product_id', $productId)->first();
            if (! $item) {
                throw ValidationException::withMessages(['product_id' => 'Produk tidak ada di nota ini.']);
            }

            $already = ReturnModel::forStore($storeId)->where('sale_id', $sale->id)->where('product_id', $productId)->sum('qty');
            if ($already + $qty > $item->qty) {
                throw ValidationException::withMessages(['qty' => 'Melebihi qty pembelian (sisa retur: '.($item->qty - $already).').']);
            }

            $itemDiscountTotal = (float) $sale->items()->sum('discount');
            $lineSubtotalTotal = (float) $sale->items()->sum('subtotal');
            $transactionDiscount = max(0, (float) $sale->discount_total - $itemDiscountTotal);
            $taxableSubtotal = max(0, $lineSubtotalTotal - $transactionDiscount);
            $cumulativePreTaxRefund = (float) $item->subtotal * (($already + $qty) / (int) $item->qty);
            $cumulativeTransactionDiscount = $lineSubtotalTotal > 0
                ? $transactionDiscount * ($cumulativePreTaxRefund / $lineSubtotalTotal)
                : 0;
            $cumulativeItemRefund = round(max(0, $cumulativePreTaxRefund - $cumulativeTransactionDiscount), 2);
            $previousItemRefund = (float) ReturnModel::forStore($storeId)
                ->where('sale_id', $sale->id)
                ->where('product_id', $productId)
                ->sum('item_refund_amount');
            $itemRefundAmount = round(max(0, $cumulativeItemRefund - $previousItemRefund), 2);

            $cumulativePpnRefund = $taxableSubtotal > 0
                ? round((float) $sale->ppn_amount * ($cumulativeItemRefund / $taxableSubtotal), 2)
                : 0;
            $previousPpnRefund = (float) ReturnModel::forStore($storeId)
                ->where('sale_id', $sale->id)
                ->where('product_id', $productId)
                ->sum('ppn_refund_amount');
            $ppnRefundAmount = round(max(0, $cumulativePpnRefund - $previousPpnRefund), 2);

            $product = Product::forStore($storeId)->lockForUpdate()->findOrFail($productId);
            $before = (int) $product->stock;
            $product->increment('stock', $qty);

            // Increment StockLensMatrix for lens items with SPH/CYL/ADD specs
            if ($item->sph !== null && $item->cyl !== null) {
                $matrix = StockLensMatrix::forStore($storeId)
                    ->where('product_id', $product->id)
                    ->where('sph', (float) $item->sph)
                    ->where('cyl', (float) $item->cyl)
                    ->where('add_val', (float) $item->add_val)
                    ->lockForUpdate()
                    ->first();

                if ($matrix) {
                    $matrix->increment('stock_qty', $qty);
                } else {
                    StockLensMatrix::create([
                        'store_id' => $storeId,
                        'product_id' => $product->id,
                        'sph' => (float) $item->sph,
                        'cyl' => (float) $item->cyl,
                        'add_val' => (float) $item->add_val,
                        'stock_qty' => $qty,
                        'min_stock_alert' => 2,
                    ]);
                }
            }

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
                'refund_amount' => $itemRefundAmount + $ppnRefundAmount,
                'item_refund_amount' => $itemRefundAmount,
                'ppn_refund_amount' => $ppnRefundAmount,
                'reason' => $reason,
                'status' => 'approved',
            ]);
        });
    }
}
