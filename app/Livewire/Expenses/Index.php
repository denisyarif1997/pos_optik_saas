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
class Index extends Component
{
    #[Validate('required|date')]
    public string $spent_at = '';

    #[Validate('required|string|max:60')]
    public string $category = 'operasional';

    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate('required|numeric|min:1')]
    public float $amount = 0;

    public ?int $cashAccountId = null;

    public bool $showForm = false;

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

    private function selectedAccountBalance(): float
    {
        if (! $this->cashAccountId) {
            return 0.0;
        }

        return CashAccount::balancesForStore($this->storeId())[$this->cashAccountId] ?? 0.0;
    }

    public function mount(): void
    {
        $this->spent_at = now()->format('Y-m-d');
        $this->cashAccountId = CashAccount::defaultActiveAccountIdForStore($this->storeId());
    }

    public function save(): void
    {
        $this->validate();
        $storeId = $this->storeId();

        Expense::create([
            'store_id' => $storeId,
            'created_by' => auth()->id(),
            'spent_at' => $this->spent_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'cash_account_id' => $this->cashAccountId,
        ]);

        $this->reset(['description', 'amount']);
        session()->flash('success', 'Pengeluaran tercatat.');
        $this->dispatch('notify', type: 'success', message: 'Pengeluaran tercatat.');
    }

    public function delete(int $id): void
    {
        Expense::forStore($this->storeId())->findOrFail($id)->delete();

        $this->dispatch('notify', type: 'success', message: 'Pengeluaran berhasil dihapus.');
    }

    public function render()
    {
        $storeId = $this->storeId();

        return view('livewire.expenses.index', [
            'expenses' => Expense::forStore($storeId)->with('cashAccount')->latest('spent_at')->limit(30)->get(),
            'cashAccountOptions' => CashAccount::activeOptionsForStore($storeId),
            'selectedBalance' => $this->selectedAccountBalance(),
            'monthTotal' => Expense::forStore($storeId)->whereMonth('spent_at', now()->month)->whereYear('spent_at', now()->year)->sum('amount'),
        ])->title('Pengeluaran');
    }

    private function storeId(): int
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return $storeId;
    }
}
