<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Product;
use App\Models\ReturnModel;
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

        $returnsBySale = ReturnModel::forStore($storeId)
            ->where('status', 'approved')
            ->select('sale_id', DB::raw('SUM(refund_amount) as refund_total'))
            ->groupBy('sale_id');
        $salesWithReturns = (clone $salesQ)
            ->leftJoinSub($returnsBySale, 'approved_returns', fn ($join) => $join->on('sales.id', '=', 'approved_returns.sale_id'));
        $refundExpression = 'COALESCE(approved_returns.refund_total, 0)';
        $netSalesExpression = "CASE WHEN sales.total > {$refundExpression} THEN sales.total - {$refundExpression} ELSE 0 END";
        $netCollectedExpression = "CASE WHEN sales.paid_total > {$refundExpression} THEN sales.paid_total - {$refundExpression} ELSE 0 END";

        $revenue = (float) (clone $salesWithReturns)->sum(DB::raw($netSalesExpression));
        $refundTotal = (float) (clone $salesWithReturns)->sum(DB::raw($refundExpression));
        $saleCount = (clone $salesQ)->count();
        $collected = (float) (clone $salesWithReturns)->sum(DB::raw($netCollectedExpression));
        $receivable = max(0, $revenue - $collected);

        $returnsBySaleProduct = ReturnModel::forStore($storeId)
            ->where('status', 'approved')
            ->select(
                'sale_id',
                'product_id',
                DB::raw('SUM(qty) as returned_qty'),
                DB::raw('SUM(refund_amount) as refund_total'),
            )
            ->groupBy('sale_id', 'product_id');
        $saleItemsInPeriod = SaleItem::forStore($storeId)
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereDate('sales.sold_at', '>=', $this->from)
            ->whereDate('sales.sold_at', '<=', $this->to);
        $returnedQtyExpression = 'COALESCE(approved_item_returns.returned_qty, 0)';
        $returnedAmountExpression = 'COALESCE(approved_item_returns.refund_total, 0)';
        $netItemQtyExpression = "CASE WHEN sale_items.qty > {$returnedQtyExpression} THEN sale_items.qty - {$returnedQtyExpression} ELSE 0 END";
        $netItemRevenueExpression = "CASE WHEN sale_items.subtotal > {$returnedAmountExpression} THEN sale_items.subtotal - {$returnedAmountExpression} ELSE 0 END";

        $cogs = (float) (clone $saleItemsInPeriod)
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->leftJoinSub($returnsBySaleProduct, 'approved_item_returns', fn ($join) => $join
                ->on('sale_items.sale_id', '=', 'approved_item_returns.sale_id')
                ->on('sale_items.product_id', '=', 'approved_item_returns.product_id'))
            ->sum(DB::raw("{$netItemQtyExpression} * products.cost_price"));

        // Combined metrics
        $totalRevenue = $revenue;
        $totalCount = $saleCount;
        $totalCollected = $collected;
        $totalReceivable = $receivable;

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
        $topProducts = (clone $saleItemsInPeriod)
            ->leftJoinSub($returnsBySaleProduct, 'approved_item_returns', fn ($join) => $join
                ->on('sale_items.sale_id', '=', 'approved_item_returns.sale_id')
                ->on('sale_items.product_id', '=', 'approved_item_returns.product_id'))
            ->select(
                'sale_items.product_id',
                DB::raw("SUM({$netItemQtyExpression}) as qty"),
                DB::raw("SUM({$netItemRevenueExpression}) as omzet"),
            )
            ->groupBy('sale_items.product_id')
            ->havingRaw("SUM({$netItemQtyExpression}) > 0")
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

        $paymentMethodMap = [];
        foreach ($paymentMethods as $pm) {
            $paymentMethodMap[$pm->method] = (float) $pm->total;
        }

        // Daily trend (combined sales + optical orders)
        $dailySales = (clone $salesWithReturns)
            ->select(
                DB::raw('DATE(sales.sold_at) as date'),
                DB::raw("SUM({$netSalesExpression}) as total"),
                DB::raw('COUNT(sales.id) as count'),
            )
            ->groupBy('date')
            ->get();

        $dailyMap = [];
        foreach ($dailySales as $d) {
            $dailyMap[$d->date] = ['date' => $d->date, 'sale' => (float) $d->total, 'optical' => 0.0, 'count' => (int) $d->count];
        }
        ksort($dailyMap);
        $dailyTrend = array_values($dailyMap);

        // Transaction detail (recent 50 combined)
        // Aggregate cash-account names and receiver names per sale
        $salePaymentMeta = DB::table('payments')
            ->where('payments.store_id', $storeId)
            ->whereDate('payments.paid_at', '>=', $this->from)
            ->whereDate('payments.paid_at', '<=', $this->to)
            ->leftJoin('cash_accounts', 'cash_accounts.id', '=', 'payments.cash_account_id')
            ->leftJoin('users as recv', 'recv.id', '=', 'payments.received_by')
            ->select(
                'payments.sale_id',
                DB::raw("GROUP_CONCAT(DISTINCT cash_accounts.name ORDER BY cash_accounts.name SEPARATOR ', ') as cash_names"),
                DB::raw("GROUP_CONCAT(DISTINCT recv.name ORDER BY recv.name SEPARATOR ', ') as receiver_names"),
            )
            ->groupBy('payments.sale_id')
            ->get()
            ->keyBy('sale_id');

        $saleTransactions = (clone $salesWithReturns)
            ->select('sales.*', DB::raw("{$refundExpression} as refund_total"))
            ->with('customer', 'cashier')
            ->orderByDesc('sales.sold_at')
            ->limit(50)
            ->get()
            ->map(function (Sale $sale) use ($salePaymentMeta): array {
                $refund = (float) $sale->refund_total;
                $meta = $salePaymentMeta->get($sale->id);

                return [
                    'type' => 'Penjualan',
                    'code' => $sale->code,
                    'date' => $sale->sold_at,
                    'customer' => $sale->customer?->name ?? 'Umum',
                    'total' => max(0, (float) $sale->total - $refund),
                    'paid' => max(0, (float) $sale->paid_total - $refund),
                    'status' => $sale->payment_status,
                    'cash_name' => $meta?->cash_names ?? '-',
                    'processed_by' => $sale->cashier?->name ?? ($meta?->receiver_names ?? '-'),
                ];
            });

        $recentTransactions = $saleTransactions
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
            'refundTotal' => $refundTotal,
            'saleCount' => $saleCount,
            'opticalRevenue' => 0.0,
            'opticalCount' => 0,
        ])->title('Laporan');
    }
}
