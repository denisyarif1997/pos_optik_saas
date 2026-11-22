<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
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

    public bool $showForm = false;

    public function mount(): void
    {
        $this->spent_at = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        Expense::create([
            'store_id' => $storeId,
            'created_by' => auth()->id(),
            'spent_at' => $this->spent_at,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
        ]);

        $this->reset(['description', 'amount']);
        session()->flash('success', 'Pengeluaran tercatat.');
    }

    public function delete(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        Expense::forStore($storeId)->findOrFail($id)->delete();
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.expenses.index', [
            'expenses' => Expense::forStore($storeId)->latest('spent_at')->limit(30)->get(),
            'monthTotal' => Expense::forStore($storeId)->whereMonth('spent_at', now()->month)->whereYear('spent_at', now()->year)->sum('amount'),
        ])->title('Pengeluaran');
    }
}
