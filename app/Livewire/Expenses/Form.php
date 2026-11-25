<?php

namespace App\Livewire\Expenses;

use App\Models\CashAccount;
use App\Models\Expense;
use Closure;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $expenseId = null;

    #[Validate('required|date')]
    public string $spent_at = '';

    #[Validate('required|string|max:60')]
    public string $category = 'operasional';

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
                'bail',
                'required',
                'integer',
                Rule::exists('cash_accounts', 'id')
                    ->where(fn ($query) => $query->where('store_id', $this->storeId())->where('is_active', true)),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $balance = $this->selectedAccountBalance();

                    if ($balance <= 0) {
                        $fail('Tidak bisa mencatat pengeluaran karena nilai kas akun ini 0.');

                        return;
                    }

                    if ($this->amount > $balance) {
                        $fail('Nominal pengeluaran melebihi saldo kas akun ini (saldo tersedia: Rp '.number_format($balance, 0, ',', '.').').');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cashAccountId.required' => 'Pilih akun kas sumber dana.',
            'cashAccountId.exists' => 'Akun kas yang dipilih tidak valid atau tidak aktif.',
        ];
    }

    /**
     * Balance of the selected cash account. When editing, the expense being edited
     * is added back so its own deduction cannot block correcting the record.
     */
    private function selectedAccountBalance(): float
    {
        if (! $this->cashAccountId) {
            return 0.0;
        }

        $balance = CashAccount::balancesForStore($this->storeId())[$this->cashAccountId] ?? 0.0;

        if ($this->expenseId) {
            $existing = Expense::forStore($this->storeId())->find($this->expenseId);

            if ($existing && (int) $existing->cash_account_id === $this->cashAccountId) {
                $balance += (float) $existing->amount;
            }
        }

        return $balance;
    }

    public function mount(?int $id = null): void
    {
        $storeId = $this->storeId();

        // Load cash accounts for this store
        $this->cashAccountOptions = CashAccount::activeOptionsForStore($storeId);

        if ($id) {
            $e = Expense::forStore($storeId)->findOrFail($id);
            $this->expenseId = $e->id;
            $this->spent_at = $e->spent_at->format('Y-m-d');
            $this->category = $e->category;
            $this->description = $e->description;
            $this->amount = (float) $e->amount;
            $this->cashAccountId = $e->cash_account_id;
        } else {
            $this->spent_at = now()->format('Y-m-d');

            // Default to the store's cash account, matching the previous behaviour.
            $this->cashAccountId = CashAccount::defaultActiveAccountIdForStore($storeId);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = $this->storeId();

        $data = [
            'spent_at' => $this->spent_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'cash_account_id' => $this->cashAccountId,
        ];

        if ($this->expenseId) {
            $e = Expense::forStore($storeId)->findOrFail($this->expenseId);
            $this->authorize('update', $e);
            $e->update($data);
        } else {
            $this->authorize('create', Expense::class);
            $data['store_id'] = $storeId;
            $data['created_by'] = auth()->id();
            Expense::create($data);
        }

        session()->flash('success', $this->expenseId ? 'Pengeluaran diperbarui.' : 'Pengeluaran ditambahkan.');
        $this->dispatch('notify', type: 'success', message: $this->expenseId ? 'Pengeluaran berhasil diperbarui.' : 'Pengeluaran berhasil ditambahkan.');

        $this->redirectRoute('expenses.index', navigate: true);
    }

    public function render()
    {
        $storeId = $this->storeId();

        return view('livewire.expenses.form', [
            'cashAccountBalances' => CashAccount::balancesForStore($storeId),
            'selectedBalance' => $this->selectedAccountBalance(),
        ])->title($this->expenseId ? 'Ubah Pengeluaran' : 'Tambah Pengeluaran');
    }

    private function storeId(): int
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return $storeId;
    }
}
