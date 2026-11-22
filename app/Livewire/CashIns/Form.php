<?php

namespace App\Livewire\CashIns;

use App\Models\CashAccount;
use App\Models\CashIn;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $cashInId = null;

    #[Validate('required|date')]
    public string $received_at = '';

    #[Validate('required|string|max:60')]
    public string $category = 'penjualan';

    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate('required|numeric|min:1')]
    public float $amount = 0;

    public ?int $cashAccountId = null;

    public string $cashAccountName = '';

    // Cash account options for the dropdown
    public array $cashAccountOptions = [];

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        // Load cash accounts for this store
        $accounts = CashAccount::forStore($storeId)->active()->orderBy('name')->get();
        $this->cashAccountOptions = $accounts->pluck('name', 'id')->toArray();

        if ($id) {
            $c = CashIn::forStore($storeId)->findOrFail($id);
            $this->cashInId = $c->id;
            $this->received_at = $c->received_at->format('Y-m-d');
            $this->category = $c->category;
            $this->description = $c->description;
            $this->amount = (float) $c->amount;
            // Set cash account if exists
            if ($c->cash_account_id) {
                $this->cashAccountId = $c->cash_account_id;
                $this->cashAccountName = $c->cashAccount->name ?? '';
            }
        } else {
            $this->received_at = now()->format('Y-m-d');
            // Set default cash account (Kas Toko) if available
            $defaultAccount = CashAccount::forStore($storeId)->where('type', 'cash')->first();
            if ($defaultAccount) {
                $this->cashAccountId = $defaultAccount->id;
                $this->cashAccountName = $defaultAccount->name;
            }
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = [
            'received_at' => $this->received_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'cash_account_id' => $this->cashAccountId,
        ];

        if ($this->cashInId) {
            $c = CashIn::forStore($storeId)->findOrFail($this->cashInId);
            $c->update($data);
        } else {
            $data['store_id'] = $storeId;
            $data['created_by'] = auth()->id();
            CashIn::create($data);
        }

        $this->redirectRoute('cash-ins.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.cash-ins.form')->title($this->cashInId ? 'Ubah Kas Masuk' : 'Tambah Kas Masuk');
    }
}
