<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public string $from;

    public string $to;

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $salesQ = Sale::forStore($storeId)
            ->whereDate('sold_at', '>=', $this->from)
            ->whereDate('sold_at', '<=', $this->to);

        $revenue = (float) (clone $salesQ)->sum('total');
        $saleCount = (clone $salesQ)->count();
        $collected = (float) (clone $salesQ)->sum('paid_total');
        $receivable = max(0, $revenue - $collected);

        $cogs = (float) SaleItem::forStore($storeId)
            ->whereHas('sale', fn ($q) => $q
                ->whereDate('sold_at', '>=', $this->from)
                ->whereDate('sold_at', '<=', $this->to))
            ->with('product')
            ->get()
            ->sum(fn ($i) => (int) $i->qty * (float) ($i->product?->cost_price ?? 0));

        // Paid Optical Orders (treated as sales)
        $opticalQ = OpticalOrder::forStore($storeId)
            ->where('status', 'COMPLETED')
            ->whereDate('taken_at', '>=', $this->from)
            ->whereDate('taken_at', '<=', $this->to);

        $opticalRevenue = (float) (clone $opticalQ)->sum('total');
        $opticalCount = (clone $opticalQ)->count();
        $opticalCollected = (float) (clone $opticalQ)->sum('dp_total');
        $opticalReceivable = max(0, $opticalRevenue - $opticalCollected);

        // Combined metrics
        $totalRevenue = $revenue + $opticalRevenue;
        $totalCount = $saleCount + $opticalCount;
        $totalCollected = $collected + $opticalCollected;
        $totalReceivable = $receivable + $opticalReceivable;

        $grossProfit = $totalRevenue - $cogs;

        if (Schema::hasTable('expenses')) {
            $expenseTotal = (float) Expense::forStore($storeId)
                ->whereDate('spent_at', '>=', $this->from)
                ->whereDate('spent_at', '<=', $this->to)
                ->sum('amount');
        } else {
            $expenseTotal = 0;
        }

        $netProfit = $grossProfit - $expenseTotal;

        // Top products (from Sale items)
        $topProducts = SaleItem::forStore($storeId)
            ->select('product_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(subtotal) as omzet'))
            ->whereHas('sale', fn ($q) => $q
                ->whereDate('sold_at', '>=', $this->from)
                ->whereDate('sold_at', '<=', $this->to))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->limit(8)
            ->with('product')
            ->get();

        // Payment method breakdown (Sales + OpticalOrder payments)
        $paymentMethods = DB::table('payments')
            ->where('store_id', $storeId)
            ->whereDate('paid_at', '>=', $this->from)
            ->whereDate('paid_at', '<=', $this->to)
            ->select('method', DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get();

        $opticalPayments = DB::table('optical_order_payments')
            ->where('store_id', $storeId)
            ->whereDate('paid_at', '>=', $this->from)
            ->whereDate('paid_at', '<=', $this->to)
            ->select('method', DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get();

        $paymentMethodMap = [];
        foreach ($paymentMethods as $pm) {
            $paymentMethodMap[$pm->method] = (float) $pm->total;
        }
        foreach ($opticalPayments as $pm) {
            $paymentMethodMap[$pm->method] = (float) ($paymentMethodMap[$pm->method] ?? 0) + (float) $pm->total;
        }

        // Daily trend (combined sales + optical orders)
        $dailySales = DB::table('sales')
            ->where('store_id', $storeId)
            ->whereDate('sold_at', '>=', $this->from)
            ->whereDate('sold_at', '<=', $this->to)
            ->select(DB::raw('DATE(sold_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->get();

        $dailyOptical = DB::table('optical_orders')
            ->where('store_id', $storeId)
            ->where('status', 'COMPLETED')
            ->whereDate('taken_at', '>=', $this->from)
            ->whereDate('taken_at', '<=', $this->to)
            ->select(DB::raw('DATE(taken_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->get();

        $dailyMap = [];
        foreach ($dailySales as $d) {
            $dailyMap[$d->date] = ['date' => $d->date, 'sale' => (float) $d->total, 'optical' => 0, 'count' => (int) $d->count];
        }
        foreach ($dailyOptical as $d) {
            if (isset($dailyMap[$d->date])) {
                $dailyMap[$d->date]['optical'] = (float) $d->total;
                $dailyMap[$d->date]['count'] += (int) $d->count;
            } else {
                $dailyMap[$d->date] = ['date' => $d->date, 'sale' => 0, 'optical' => (float) $d->total, 'count' => (int) $d->count];
            }
        }
        ksort($dailyMap);
        $dailyTrend = array_values($dailyMap);

        // Transaction detail (recent 50 combined)
        $saleTransactions = (clone $salesQ)
            ->with('customer')
            ->orderByDesc('sold_at')
            ->limit(50)
            ->get()
            ->map(fn ($s) => [
                'type' => 'Penjualan',
                'code' => $s->code,
                'date' => $s->sold_at,
                'customer' => $s->customer?->name ?? 'Umum',
                'total' => (float) $s->total,
                'paid' => (float) $s->paid_total,
                'status' => $s->payment_status,
            ]);

        $opticalTransactions = $opticalQ
            ->with('customer')
            ->orderByDesc('taken_at')
            ->limit(50)
            ->get()
            ->map(fn ($o) => [
                'type' => 'Optical',
                'code' => $o->code,
                'date' => $o->taken_at,
                'customer' => $o->customer?->name ?? 'Umum',
                'total' => (float) $o->total,
                'paid' => (float) $o->dp_total,
                'status' => $o->status,
            ]);

        $recentTransactions = $saleTransactions->concat($opticalTransactions)
            ->sortByDesc(fn ($t) => $t['date'])
            ->take(50)
            ->values();

        // Low stock
        $lowStock = Product::forStore($storeId)
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        return view('livewire.reports.index', [
            'revenue' => $totalRevenue,
            'count' => $totalCount,
            'collected' => $totalCollected,
            'receivable' => $totalReceivable,
            'expenseTotal' => $expenseTotal,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'topProducts' => $topProducts,
            'lowStock' => $lowStock,
            'paymentMethods' => $paymentMethodMap,
            'dailyTrend' => $dailyTrend,
            'recentTransactions' => $recentTransactions,
            'saleRevenue' => $revenue,
            'opticalRevenue' => $opticalRevenue,
            'saleCount' => $saleCount,
            'opticalCount' => $opticalCount,
        ])->title('Laporan');
    }
}
