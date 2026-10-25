<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $supplierId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $s = Supplier::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $s);
            $this->supplierId = $s->id;
            $this->name = $s->name;
            $this->phone = $s->phone ?? '';
            $this->is_active = (bool) $s->is_active;
        } else {
            $this->authorize('create', Supplier::class);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = ['name' => $this->name, 'phone' => $this->phone ?: null, 'is_active' => $this->is_active];

        if ($this->supplierId) {
            $s = Supplier::forStore($storeId)->findOrFail($this->supplierId);
            $this->authorize('update', $s);
            $s->update($data);
        } else {
            $this->authorize('create', Supplier::class);
            $data['store_id'] = $storeId;
            Supplier::create($data);
        }

        $this->redirectRoute('suppliers.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.suppliers.form')->title($this->supplierId ? 'Ubah Supplier' : 'Tambah Supplier');
    }
}
