<?php

namespace App\Livewire\Dashboard;

use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\Sale;
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

        $todaySales = (float) Sale::forStore($storeId)->whereDate('sold_at', today())->sum('total');
        $todayCount = Sale::forStore($storeId)->whereDate('sold_at', today())->count();
        $yesterdaySales = (float) Sale::forStore($storeId)->whereDate('sold_at', today()->subDay())->sum('total');
        $openOrders = OpticalOrder::forStore($storeId)->whereNotIn('status', ['selesai', 'dibatalkan', 'diambil'])->count();
        $lowStock = Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count();
        $readyOrders = OpticalOrder::forStore($storeId)->where('status', 'siap_diambil')->with('customer')->latest()->limit(5)->get();
        $unpaidQuery = Sale::forStore($storeId)->where('payment_status', '!=', 'lunas');
        $unpaid = max(0, (float) $unpaidQuery->sum('total') - (float) $unpaidQuery->sum('paid_total'));

        $growth = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : ($todaySales > 0 ? 100 : 0);
        $weekly = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $weekly[] = [
                'label' => now()->subDays($i)->format('d'),
                'value' => (float) Sale::forStore($storeId)->whereDate('sold_at', $d)->sum('total'),
            ];
        }
        $maxWeekly = max(array_column($weekly, 'value') ?: [1]);

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
            'recentSales' => Sale::forStore($storeId)->latest('sold_at')->limit(8)->get(),
            'weekly' => $weekly,
            'maxWeekly' => $maxWeekly,
        ])->title('Dashboard');
    }
}
