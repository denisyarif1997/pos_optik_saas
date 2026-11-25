<?php

namespace App\Livewire\CashIns;

use App\Models\CashAccount;
use App\Models\CashIn;
use Illuminate\Validation\Rule;
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

    // Cash account options for the dropdown
    public array $cashAccountOptions = [];

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'cashAccountId' => [
                'required',
                'integer',
                Rule::exists('cash_accounts', 'id')
                    ->where(fn ($query) => $query->where('store_id', $this->storeId())->where('is_active', true)),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cashAccountId.required' => 'Pilih akun kas tujuan.',
            'cashAccountId.exists' => 'Akun kas yang dipilih tidak valid atau tidak aktif.',
        ];
    }

    public function mount(?int $id = null): void
    {
        $storeId = $this->storeId();

        // Load cash accounts for this store
        $this->cashAccountOptions = CashAccount::activeOptionsForStore($storeId);

        if ($id) {
            $c = CashIn::forStore($storeId)->findOrFail($id);
            $this->cashInId = $c->id;
            $this->received_at = $c->received_at->format('Y-m-d');
            $this->category = $c->category;
            $this->description = $c->description;
            $this->amount = (float) $c->amount;
            $this->cashAccountId = $c->cash_account_id;
        } else {
            $this->received_at = now()->format('Y-m-d');

            // Default to the store's cash account, matching the previous behaviour.
            $this->cashAccountId = CashAccount::defaultActiveAccountIdForStore($storeId);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = $this->storeId();

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

        session()->flash('success', $this->cashInId ? 'Kas masuk diperbarui.' : 'Kas masuk ditambahkan.');
        $this->dispatch('notify', type: 'success', message: $this->cashInId ? 'Kas masuk berhasil diperbarui.' : 'Kas masuk berhasil ditambahkan.');

        $this->redirectRoute('cash-ins.index', navigate: true);
    }

    public function render()
    {
        $storeId = $this->storeId();

        return view('livewire.cash-ins.form', [
            'cashAccountBalances' => CashAccount::balancesForStore($storeId),
        ])->title($this->cashInId ? 'Ubah Kas Masuk' : 'Tambah Kas Masuk');
    }

    private function storeId(): int
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return $storeId;
    }
}
