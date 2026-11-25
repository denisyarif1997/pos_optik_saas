<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        Supplier::create([
            'store_id' => $storeId,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'is_active' => true,
        ]);
        $this->reset(['name', 'phone']);
        session()->flash('success', 'Supplier ditambahkan.');
        $this->dispatch('notify', type: 'success', message: 'Supplier ditambahkan.');
    }

    public function toggle(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $s = Supplier::forStore($storeId)->findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);

        $this->dispatch('notify', type: 'success', message: 'Status supplier diperbarui.');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.suppliers.index', [
            'suppliers' => Supplier::forStore($storeId)
                ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%'))
                ->orderBy('name')
                ->paginate(12),
        ])->title('Supplier');
    }
}
