<?php

namespace App\Http\Controllers;

use App\Models\OpticalOrder;
use App\Models\Prescription;
use App\Models\Sale;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function sale(Request $request, int $id)
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->with(['items.product', 'payments.cashAccount', 'customer', 'returns'])->findOrFail($id);
        $request->user()->can('view', $sale) || abort(403);

        // Hitung retur per produk untuk cetak nota
        $returnsByItem = [];
        $totalRefund = 0.0;
        foreach ($sale->returns as $ret) {
            if (! isset($returnsByItem[$ret->product_id])) {
                $returnsByItem[$ret->product_id] = ['qty' => 0, 'amount' => 0.0];
            }
            $returnsByItem[$ret->product_id]['qty'] += (int) $ret->qty;
            $returnsByItem[$ret->product_id]['amount'] += (float) $ret->refund_amount;
            $totalRefund += (float) $ret->refund_amount;
        }

        return response()->view('print.sale', [
            'sale' => $sale,
            'store' => app('activeStore')->get($request->user()),
            'returnsByItem' => $returnsByItem,
            'totalRefund' => $totalRefund,
        ]);
    }

    public function opticalOrder(Request $request, int $id)
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->with(['customer', 'items', 'prescription', 'payments.cashAccount'])->findOrFail($id);
        $request->user()->can('view', $order) || abort(403);

        return response()->view('print.optical-order', [
            'order' => $order,
            'store' => app('activeStore')->get($request->user()),
        ]);
    }

    public function prescription(Request $request, int $id)
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $rx = Prescription::forStore($storeId)->with('customer')->findOrFail($id);

        return response()->view('print.prescription', [
            'rx' => $rx,
            'store' => app('activeStore')->get($request->user()),
        ]);
    }
}
