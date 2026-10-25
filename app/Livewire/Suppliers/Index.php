<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        Supplier::create(['store_id' => $storeId, 'name' => $this->name, 'phone' => $this->phone ?: null, 'is_active' => true]);
        $this->reset(['name', 'phone']);
        session()->flash('success', 'Supplier ditambahkan.');
    }

    public function toggle(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $s = Supplier::forStore($storeId)->findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.suppliers.index', [
            'suppliers' => Supplier::forStore($storeId)->orderBy('name')->get(),
        ])->title('Supplier');
    }
}
