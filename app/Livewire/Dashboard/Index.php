<?php

namespace App\Livewire\Dashboard;

use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        $store = app('activeStore')->get();
        abort_unless($store, 403);
        $storeId = $store->id;

        $today = today();
        $returnsBySale = ReturnModel::forStore($storeId)
            ->where('status', 'approved')
            ->select('sale_id', DB::raw('SUM(refund_amount) as refund_total'))
            ->groupBy('sale_id');
        $refundExpression = 'COALESCE(approved_returns.refund_total, 0)';
        $netSalesExpression = "CASE WHEN sales.total > {$refundExpression} THEN sales.total - {$refundExpression} ELSE 0 END";
        $salesByDate = Sale::forStore($storeId)
            ->leftJoinSub($returnsBySale, 'approved_returns', fn ($join) => $join->on('sales.id', '=', 'approved_returns.sale_id'))
            ->whereBetween('sales.sold_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->selectRaw("DATE(sales.sold_at) as sale_date, COALESCE(SUM({$netSalesExpression}), 0) as net_total")
            ->groupByRaw('DATE(sales.sold_at)')
            ->pluck('net_total', 'sale_date');

        $todaySales = (float) $salesByDate->get($today->toDateString(), 0);
        $todayCount = Sale::forStore($storeId)->whereDate('sold_at', today())->count();
        $yesterdaySales = (float) $salesByDate->get($today->copy()->subDay()->toDateString(), 0);
        $openOrders = OpticalOrder::forStore($storeId)->whereNotIn('status', ['COMPLETED', 'BATAL'])->count();
        $lowStock = Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count();
        $readyOrders = OpticalOrder::forStore($storeId)->where('status', 'siap_diambil')->with('customer')->latest()->limit(5)->get();
        $unpaidQuery = Sale::forStore($storeId)->where('payment_status', '!=', 'lunas');
        $unpaid = max(0, (float) $unpaidQuery->sum('total') - (float) $unpaidQuery->sum('paid_total'));

        $growth = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : ($todaySales > 0 ? 100 : 0);
        $weekly = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $d = $date->toDateString();
            $weekly[] = [
                'label' => $date->format('d'),
                'value' => (float) $salesByDate->get($d, 0),
            ];
        }
        $maxWeekly = max(array_column($weekly, 'value') ?: [1]);

        $recentSales = Sale::forStore($storeId)
            ->withSum([
                'returns as approved_refund_total' => fn ($query) => $query
                    ->where('status', 'approved')
                    ->where('store_id', $storeId),
            ], 'refund_amount')
            ->latest('sold_at')
            ->limit(8)
            ->get()
            ->each(function (Sale $sale): void {
                $refundTotal = (float) $sale->approved_refund_total;
                $sale->setAttribute('net_total', max(0, (float) $sale->total - $refundTotal));
                $sale->setAttribute(
                    'return_status',
                    $refundTotal <= 0
                        ? null
                        : ($refundTotal >= (float) $sale->total ? 'Diretur' : 'Retur sebagian'),
                );
            });

        return view('livewire.dashboard.index', [
            'store' => $store,
            'todaySales' => $todaySales,
            'todayCount' => $todayCount,
            'growth' => $growth,
            'openOrders' => $openOrders,
            'lowStock' => $lowStock,
            'unpaid' => $unpaid,
            'lowStockItems' => Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->limit(5)->get(),
            'pendingOrders' => OpticalOrder::forStore($storeId)->with('customer')->whereNotIn('status', ['selesai', 'dibatalkan', 'diambil'])->latest()->limit(5)->get(),
            'readyOrders' => $readyOrders,
            'recentSales' => $recentSales,
            'weekly' => $weekly,
            'maxWeekly' => $maxWeekly,
        ])->title('Dashboard');
    }
}
