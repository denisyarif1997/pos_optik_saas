<?php

namespace App\Livewire\Reports;

use App\Models\CashAccount;
use App\Models\CashIn;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CashInReport extends Component
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

        $query = CashIn::forStore($storeId)
            ->with(['cashAccount', 'creator'])
            ->when($this->from, fn ($q) => $q->whereDate('received_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('received_at', '<=', $this->to))
            ->when($this->cashAccountId, fn ($q) => $q->where('cash_account_id', $this->cashAccountId))
            ->latest('received_at');

        $cashIns = $query->paginate(20);

        $total = (float) $query->sum('amount');

        $byAccount = CashIn::forStore($storeId)
            ->when($this->from, fn ($q) => $q->whereDate('received_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('received_at', '<=', $this->to))
            ->when($this->cashAccountId, fn ($q) => $q->where('cash_account_id', $this->cashAccountId))
            ->join('cash_accounts', 'cash_accounts.id', '=', 'cash_ins.cash_account_id')
            ->select('cash_accounts.name as account_name', DB::raw('SUM(cash_ins.amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('cash_accounts.name')
            ->get();

        return view('livewire.reports.cash-in', [
            'cashIns' => $cashIns,
            'total' => $total,
            'byAccount' => $byAccount,
        ])->title('Laporan Kas Masuk');
    }
}
