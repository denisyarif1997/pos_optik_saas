<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public string $period = '7d';

    public ?int $selectedStoreId = null;

    public function mount(): void
    {
        $this->selectedStoreId = app('activeStore')->id();
    }

    public function render()
    {
        $activeStore = app('activeStore')->get();
        abort_unless($activeStore, 403);

        $user = auth()->user();
        $stores = $user ? $user->activeStores()->orderBy('name')->get() : collect([$activeStore]);
        if ($stores->isEmpty()) {
            $stores = collect([$activeStore]);
        }

        $store = $stores->firstWhere('id', $this->selectedStoreId) ?? $activeStore;
        $storeId = $store->id;

        $today = today();
        [$rangeStart, $rangeEnd] = match ($this->period) {
            '30d' => [$today->copy()->subDays(29)->startOfDay(), $today->copy()->endOfDay()],
            'month' => [$today->copy()->startOfMonth()->startOfDay(), $today->copy()->endOfDay()],
            'year' => [$today->copy()->startOfYear()->startOfDay(), $today->copy()->endOfDay()],
            default => [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()],
        };

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
        $growth = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : ($todaySales > 0 ? 100 : 0);

        $periodRevenue = (float) Sale::forStore($storeId)
            ->leftJoinSub($returnsBySale, 'approved_returns', fn ($join) => $join->on('sales.id', '=', 'approved_returns.sale_id'))
            ->whereBetween('sales.sold_at', [$rangeStart, $rangeEnd])
            ->sum(DB::raw($netSalesExpression));

        $periodSaleCount = Sale::forStore($storeId)
            ->whereBetween('sold_at', [$rangeStart, $rangeEnd])
            ->count();

        $readyFrameCount = (int) Product::forStore($storeId)
            ->where('is_active', true)
            ->where('type', 'FRAME')
            ->sum('stock');
        $totalLensStock = (int) Product::forStore($storeId)
            ->where('is_active', true)
            ->whereIn('type', ['STOCK_LENS', 'RX_LENS', 'CONTACT_LENS'])
            ->sum('stock');
        $totalActiveProducts = Product::forStore($storeId)->where('is_active', true)->count();
        $lowStock = Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count();

        $unpaidQuery = Sale::forStore($storeId)->where('payment_status', '!=', 'lunas');
        $unpaid = max(0, (float) $unpaidQuery->sum('total') - (float) $unpaidQuery->sum('paid_total'));

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

        $chartSeries = $this->buildChartSeries($storeId, $rangeStart, $rangeEnd);
        $maxChartVal = 1;
        foreach ($chartSeries as $bucket) {
            $maxChartVal = max($maxChartVal, $bucket['lenses'], $bucket['frames'], $bucket['returns']);
        }

        $recentSales = Sale::forStore($storeId)
            ->with('customer:id,name')
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
            'stores' => $stores,
            'todaySales' => $todaySales,
            'todayCount' => $todayCount,
            'periodRevenue' => $periodRevenue,
            'readyFrameCount' => $readyFrameCount,
            'totalLensStock' => $totalLensStock,
            'totalActiveProducts' => $totalActiveProducts,
            'growth' => $growth,
            'lowStock' => $lowStock,
            'unpaid' => $unpaid,
            'lowStockItems' => Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->limit(5)->get(),
            'readyOrders' => collect(),
            'recentSales' => $recentSales,
            'weekly' => $weekly,
            'maxWeekly' => $maxWeekly,
            'chartSeries' => $chartSeries,
            'maxChartVal' => $maxChartVal,
            'rangeFrom' => $rangeStart->toDateString(),
            'rangeTo' => $rangeEnd->toDateString(),
        ])->title('Dashboard');
    }

    /**
     * Build multi-series analytics data grouped by date or month for the active period.
     *
     * @return array<int, array{label: string, lenses: int, frames: int, orders: int, returns: int}>
     */
    private function buildChartSeries(int $storeId, CarbonInterface $start, CarbonInterface $end): array
{
    $isYearly = $this->period === 'year';
    $groupFormat = $isYearly ? '%Y-%m' : '%Y-%m-%d';

    $itemsByBucket = SaleItem::query()
        ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
        ->join('products', 'products.id', '=', 'sale_items.product_id')
        ->where('sales.store_id', $storeId)
        ->whereBetween('sales.sold_at', [$start, $end])
        ->selectRaw("DATE_FORMAT(sales.sold_at, '{$groupFormat}') as bucket")
        ->selectRaw("SUM(CASE WHEN products.type IN ('STOCK_LENS', 'RX_LENS', 'CONTACT_LENS') THEN sale_items.qty ELSE 0 END) as lenses_qty")
        ->selectRaw("SUM(CASE WHEN products.type NOT IN ('STOCK_LENS', 'RX_LENS', 'CONTACT_LENS') THEN sale_items.qty ELSE 0 END) as frames_qty")
        ->groupBy('bucket')
        ->get()
        ->keyBy('bucket');

    $returnsByBucket = ReturnModel::forStore($storeId)
        ->where('status', 'approved')
        ->whereBetween('created_at', [$start, $end])
        ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as bucket, SUM(qty) as cnt")
        ->groupBy('bucket')
        ->pluck('cnt', 'bucket');

    $opticalOrdersByBucket = DB::table('optical_orders')
        ->where('store_id', $storeId)
        ->whereBetween('ordered_at', [$start, $end])
        ->selectRaw("DATE_FORMAT(ordered_at, '{$groupFormat}') as bucket, COUNT(*) as cnt")
        ->groupBy('bucket')
        ->pluck('cnt', 'bucket');

    $series = [];

    if ($isYearly) {
        for ($m = 1; $m <= 12; $m++) {
            $dt = $start->copy()->month($m);
            $key = $dt->format('Y-m');
            $row = $itemsByBucket->get($key);
            $series[] = [
                'label' => $dt->translatedFormat('M'),
                'lenses' => (int) ($row?->lenses_qty ?? 0),
                'frames' => (int) ($row?->frames_qty ?? 0),
                'orders' => (int) $opticalOrdersByBucket->get($key, 0),
                'returns' => (int) $returnsByBucket->get($key, 0),
            ];
        }

        return $series;
    }

    $days = $this->period === '7d' ? 7 : min(12, max(7, (int) $start->diffInDays($end) + 1));
    $cursor = $end->copy()->subDays($days - 1)->startOfDay();
    for ($i = 0; $i < $days; $i++) {
        $key = $cursor->format('Y-m-d');
        $row = $itemsByBucket->get($key);
        $series[] = [
            'label' => $cursor->format('d M'),
            'lenses' => (int) ($row?->lenses_qty ?? 0),
            'frames' => (int) ($row?->frames_qty ?? 0),
            'orders' => (int) $opticalOrdersByBucket->get($key, 0),
            'returns' => (int) $returnsByBucket->get($key, 0),
        ];
        $cursor->addDay();
    }

    return $series;
    }
}
