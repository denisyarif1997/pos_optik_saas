<?php

namespace App\Livewire\CashIns;

use App\Models\CashIn;
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

    public bool $showForm = false;

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
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        CashIn::create([
            'store_id' => $storeId,
            'created_by' => auth()->id(),
            'received_at' => $this->received_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
        ]);

        $this->reset(['description', 'amount']);
        session()->flash('success', 'Kas masuk tercatat.');
    }

    public function delete(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        CashIn::forStore($storeId)->findOrFail($id)->delete();
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.cash-ins.index', [
            'cashIns' => CashIn::forStore($storeId)->latest('received_at')->limit(30)->get(),
            'monthTotal' => CashIn::forStore($storeId)->whereMonth('received_at', now()->month)->whereYear('received_at', now()->year)->sum('amount'),
        ])->title('Kas Masuk');
    }
}
