<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function sales(Request $request): StreamedResponse
    {
        $storeId = app('activeStore')->id($request->user());
        abort_unless($storeId, 403);

        $from = $request->query('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->query('to', now()->format('Y-m-d'));

        $rows = Sale::forStore($storeId)->with('customer')
            ->whereDate('sold_at', '>=', $from)->whereDate('sold_at', '<=', $to)
            ->orderBy('sold_at')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode', 'Waktu', 'Pelanggan', 'Total', 'Dibayar', 'Status']);
            foreach ($rows as $s) {
                fputcsv($out, [$s->code, $s->sold_at->format('Y-m-d H:i'), $s->customer?->name ?? 'Umum', $s->total, $s->paid_total, $s->payment_status]);
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

        $rows = StockOpname::forStore($storeId)->with('items.product')->latest()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode', 'Tanggal', 'Status', 'SKU', 'Produk', 'Sistem', 'Aktual', 'Selisih']);
            foreach ($rows as $so) {
                foreach ($so->items as $i) {
                    fputcsv($out, [$so->code, $so->started_at?->format('Y-m-d H:i'), $so->status, $i->product?->sku, $i->product?->name, $i->qty_system, $i->qty_counted, $i->diff]);
                }
            }
            fclose($out);
        }, 'stock-opname.csv', ['Content-Type' => 'text/csv']);
    }
}
