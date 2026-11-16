<?php

namespace App\Http\Controllers;

use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function sales(Request $request): StreamedResponse
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $from = $request->query('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->query('to', now()->format('Y-m-d'));

        $sales = Sale::forStore($storeId)
            ->with('customer')
            ->whereDate('sold_at', '>=', $from)
            ->whereDate('sold_at', '<=', $to)
            ->orderBy('sold_at')
            ->get();
        $refundsBySale = ReturnModel::forStore($storeId)
            ->where('status', 'approved')
            ->whereIn('sale_id', $sales->modelKeys())
            ->select('sale_id', DB::raw('SUM(refund_amount) as refund_total'))
            ->groupBy('sale_id')
            ->pluck('refund_total', 'sale_id');

        $optical = OpticalOrder::forStore($storeId)
            ->with('customer')
            ->where('status', 'COMPLETED')
            ->whereDate('taken_at', '>=', $from)
            ->whereDate('taken_at', '<=', $to)
            ->orderBy('taken_at')
            ->get();

        return response()->streamDownload(function () use ($sales, $optical, $refundsBySale) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'Jenis', 'Kode', 'Pelanggan', 'Retur', 'Total', 'Dibayar', 'Status']);
            foreach ($sales as $s) {
                $refund = (float) $refundsBySale->get($s->id, 0);
                $total = max(0, (float) $s->total - $refund);
                $paid = max(0, (float) $s->paid_total - $refund);

                fputcsv($out, [
                    $s->sold_at->format('Y-m-d H:i'),
                    'Penjualan',
                    $s->code,
                    $s->customer?->name ?? 'Umum',
                    $refund,
                    $total,
                    $paid,
                    $s->payment_status,
                ]);
            }
            foreach ($optical as $o) {
                fputcsv($out, [
                    $o->taken_at?->format('Y-m-d H:i'),
                    'Optical',
                    $o->code,
                    $o->customer?->name ?? 'Umum',
                    0,
                    $o->total,
                    $o->dp_total,
                    $o->status,
                ]);
            }
            fclose($out);
        }, 'penjualan-'.$from.'-'.$to.'.csv', ['Content-Type' => 'text/csv']);
    }

    public function stock(Request $request): StreamedResponse
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $rows = Product::forStore($storeId)->orderBy('name')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['SKU', 'Nama', 'Tipe', 'Stok', 'Min', 'Jual', 'Pokok']);
            foreach ($rows as $p) {
                fputcsv($out, [$p->sku, $p->name, $p->type, $p->stock, $p->min_stock, $p->sell_price, $p->cost_price]);
            }
            fclose($out);
        }, 'stok.csv', ['Content-Type' => 'text/csv']);
    }

    public function stockOpname(Request $request): StreamedResponse
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $query = StockOpname::forStore($storeId)->with('items.product')->latest();

        if ($request->filled('opname_id')) {
            $query->where('id', $request->query('opname_id'));
        }

        $rows = $query->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode', 'Tanggal', 'Status', 'SKU', 'Produk', 'Sistem', 'Aktual', 'Selisih']);
            foreach ($rows as $so) {
                foreach ($so->items as $i) {
                    fputcsv($out, [$so->code, $so->conducted_at?->format('Y-m-d H:i'), $so->status, $i->product?->sku, $i->product?->name, $i->system_qty, $i->counted_qty, $i->diff]);
                }
            }
            fclose($out);
        }, 'stock-opname.csv', ['Content-Type' => 'text/csv']);
    }
}
