<?php

namespace App\Livewire\Reports;

use App\Models\Expense;
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

        $salesQ = Sale::forStore($storeId)->whereDate('sold_at', '>=', $this->from)->whereDate('sold_at', '<=', $this->to);
        $revenue = (float) (clone $salesQ)->sum('total');
        $count = (clone $salesQ)->count();
        $collected = (float) (clone $salesQ)->sum('paid_total');
        $receivable = max(0, $revenue - $collected);
        $cogs = (float) SaleItem::forStore($storeId)
            ->whereHas('sale', fn ($q) => $q->whereDate('sold_at', '>=', $this->from)->whereDate('sold_at', '<=', $this->to))
            ->with('product')
            ->get()
            ->sum(fn ($i) => (int) $i->qty * (float) ($i->product?->cost_price ?? 0));

        $grossProfit = $revenue - $cogs;

        if (Schema::hasTable('expenses')) {
            $expenseTotal = (float) Expense::forStore($storeId)->whereDate('spent_at', '>=', $this->from)->whereDate('spent_at', '<=', $this->to)->sum('amount');
        } else {
            $expenseTotal = 0;
        }

        $netProfit = $grossProfit - $expenseTotal;

        $topProducts = SaleItem::forStore($storeId)
            ->select('product_id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(subtotal) as omzet'))
            ->whereHas('sale', fn ($q) => $q->whereDate('sold_at', '>=', $this->from)->whereDate('sold_at', '<=', $this->to))
            ->groupBy('product_id')->orderByDesc('qty')->limit(8)->with('product')->get();

        return view('livewire.reports.index', [
            'revenue' => $revenue,
            'count' => $count,
            'collected' => $collected,
            'receivable' => $receivable,
            'expenseTotal' => $expenseTotal,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'topProducts' => $topProducts,
            'lowStock' => Product::forStore($storeId)->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count(),
        ])->title('Laporan');
    }
}
