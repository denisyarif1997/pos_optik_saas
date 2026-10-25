<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
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

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $e = Expense::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $e);
            $this->expenseId = $e->id;
            $this->spent_at = $e->spent_at->format('Y-m-d');
            $this->category = $e->category;
            $this->description = $e->description;
            $this->amount = (float) $e->amount;
        } else {
            $this->authorize('create', Expense::class);
            $this->spent_at = now()->format('Y-m-d');
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = ['spent_at' => $this->spent_at, 'category' => $this->category, 'description' => $this->description, 'amount' => $this->amount];

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

        $this->redirectRoute('expenses.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.expenses.form')->title($this->expenseId ? 'Ubah Pengeluaran' : 'Tambah Pengeluaran');
    }
}
