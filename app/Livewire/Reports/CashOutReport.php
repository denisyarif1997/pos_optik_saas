<?php

namespace App\Livewire\Reports;

use App\Models\CashAccount;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CashOutReport extends Component
{
    public string $from = '';

    public string $to = '';

    public ?int $cashAccountId = null;

    public array $cashAccountOptions = [];

    public function mount(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');

        $this->cashAccountOptions = CashAccount::activeOptionsForStore($storeId);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $query = Expense::forStore($storeId)
            ->with(['cashAccount', 'creator'])
            ->when($this->from, fn ($q) => $q->whereDate('spent_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('spent_at', '<=', $this->to))
            ->when($this->cashAccountId, fn ($q) => $q->where('cash_account_id', $this->cashAccountId))
            ->latest('spent_at');

        $expenses = $query->paginate(20);

        $total = (float) $query->sum('amount');

        $byAccount = Expense::forStore($storeId)
            ->when($this->from, fn ($q) => $q->whereDate('spent_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('spent_at', '<=', $this->to))
            ->when($this->cashAccountId, fn ($q) => $q->where('cash_account_id', $this->cashAccountId))
            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.cash_account_id')
            ->select('cash_accounts.name as account_name', DB::raw('SUM(expenses.amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('cash_accounts.name')
            ->get();

        return view('livewire.reports.cash-out', [
            'expenses' => $expenses,
            'total' => $total,
            'byAccount' => $byAccount,
        ])->title('Laporan Kas Keluar');
    }
}
