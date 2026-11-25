<?php

namespace App\Livewire\CashAccounts;

use App\Models\CashAccount;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public ?int $cashAccountId = null;

    public string $name = '';

    public string $type = 'cash';

    public string $statusMessage = '';

    public string $statusType = 'success';

    public function edit(int $id): void
    {
        $account = CashAccount::forStore($this->storeId())->findOrFail($id);

        $this->cashAccountId = $account->id;
        $this->name = $account->name;
        $this->type = $account->type;
    }

    public function cancelEditing(): void
    {
        $this->reset(['cashAccountId', 'name']);
        $this->type = 'cash';
    }

    public function save(): void
    {
        $storeId = $this->storeId();

        $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cash_accounts', 'name')->where('store_id', $storeId)->ignore($this->cashAccountId),
            ],
            'type' => ['required', Rule::in(['cash', 'bank'])],
        ]);

        if ($this->cashAccountId) {
            $account = CashAccount::forStore($storeId)->findOrFail($this->cashAccountId);
            $account->update(['name' => $this->name, 'type' => $this->type]);
            $this->statusMessage = 'Akun kas diperbarui.';
        } else {
            $baseCode = str()->slug($this->name) ?: 'akun-kas';
            $code = $baseCode;
            $suffix = 1;
            while (CashAccount::forStore($storeId)->where('code', $code)->exists()) {
                $code = $baseCode.'-'.$suffix++;
            }

            CashAccount::create([
                'store_id' => $storeId,
                'code' => $code,
                'name' => $this->name,
                'type' => $this->type,
                'is_active' => true,
            ]);
            $this->statusMessage = 'Akun kas ditambahkan.';
        }

        $this->statusType = 'success';
        $this->cancelEditing();

        $this->dispatch('notify', type: 'success', message: $this->statusMessage);
    }

    public function toggleActive(int $id): void
    {
        $storeId = $this->storeId();
        $account = CashAccount::forStore($storeId)->findOrFail($id);

        if ($account->is_active && CashAccount::forStore($storeId)->where('is_active', true)->count() === 1) {
            $this->statusType = 'error';
            $this->statusMessage = 'Minimal satu akun kas harus tetap aktif.';

            $this->dispatch('notify', type: 'error', message: $this->statusMessage, sticky: true);

            return;
        }

        $account->update(['is_active' => ! $account->is_active]);
        $this->statusType = 'success';
        $this->statusMessage = $account->is_active ? 'Akun kas diaktifkan.' : 'Akun kas dinonaktifkan.';

        $this->dispatch('notify', type: 'success', message: $this->statusMessage);
    }

    public function render()
    {
        $storeId = $this->storeId();
        $balances = CashAccount::balancesForStore($storeId);
        $accounts = CashAccount::forStore($storeId)->orderBy('name')->get();
        $totalBalance = 0.0;

        $rows = $accounts->map(function (CashAccount $account) use ($balances, &$totalBalance): array {
            $balance = $balances[$account->id] ?? 0.0;

            if ($account->is_active) {
                $totalBalance += $balance;
            }

            return [
                'id' => $account->id,
                'name' => $account->name,
                'type' => $account->type,
                'is_active' => $account->is_active,
                'balance' => $balance,
            ];
        })->all();

        // dd($rows);

        return view('livewire.cash-accounts.index', [
            'accounts' => $rows,
            'totalBalance' => $totalBalance,
        ])->title('Master Kas');
    }

    private function storeId(): int
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId && app('activeStore')->role() === 'owner', 403);

        return $storeId;
    }
}
