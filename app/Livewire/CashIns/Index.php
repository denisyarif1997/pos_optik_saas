<?php

namespace App\Livewire\CashIns;

use App\Models\CashAccount;
use App\Models\CashIn;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Validate('required|date')]
    public string $received_at = '';

    #[Validate('required|string|max:60')]
    public string $category = 'penjualan';

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

    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
    }

    public function mount(): void
    {
        $this->received_at = now()->format('Y-m-d');
        $this->cashAccountId = CashAccount::defaultActiveAccountIdForStore($this->storeId());
    }

    public function save(): void
    {
        $this->validate();
        $storeId = $this->storeId();

        CashIn::create([
            'store_id' => $storeId,
            'created_by' => auth()->id(),
            'received_at' => $this->received_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'cash_account_id' => $this->cashAccountId,
        ]);

        $this->reset(['description', 'amount']);
        session()->flash('success', 'Kas masuk tercatat.');
        $this->dispatch('notify', type: 'success', message: 'Kas masuk tercatat.');
    }

    public function delete(int $id): void
    {
        CashIn::forStore($this->storeId())->findOrFail($id)->delete();

        $this->dispatch('notify', type: 'success', message: 'Kas masuk berhasil dihapus.');
    }

    public function render()
    {
        $storeId = $this->storeId();

        return view('livewire.cash-ins.index', [
            'cashIns' => CashIn::forStore($storeId)->with('cashAccount')->latest('received_at')->limit(30)->get(),
            'cashAccountOptions' => CashAccount::activeOptionsForStore($storeId),
            'monthTotal' => CashIn::forStore($storeId)->whereMonth('received_at', now()->month)->whereYear('received_at', now()->year)->sum('amount'),
        ])->title('Kas Masuk');
    }

    private function storeId(): int
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return $storeId;
    }
}
