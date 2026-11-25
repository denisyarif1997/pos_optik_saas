<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $customerId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('nullable|string')]
    public string $address = '';

    #[Validate('nullable|date')]
    public ?string $birth_date = null;

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $customer = Customer::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $customer);
            $this->customerId = $customer->id;
            $this->name = $customer->name;
            $this->phone = $customer->phone ?? '';
            $this->address = $customer->address ?? '';
            $this->birth_date = $customer->birth_date?->format('Y-m-d');
        } else {
            $this->authorize('create', Customer::class);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'birth_date' => $this->birth_date ?: null,
            'is_active' => true,
        ];

        if ($this->customerId) {
            $customer = Customer::forStore($storeId)->findOrFail($this->customerId);
            $this->authorize('update', $customer);
            $customer->update($data);
        } else {
            $this->authorize('create', Customer::class);
            $data['store_id'] = $storeId;
            $customer = Customer::create($data);
            $this->customerId = $customer->id;
        }

        session()->flash('success', $this->customerId ? 'Pelanggan diperbarui.' : 'Pelanggan ditambahkan.');
        $this->dispatch('notify', type: 'success', message: $this->customerId ? 'Pelanggan berhasil diperbarui.' : 'Pelanggan berhasil ditambahkan.');

        $this->redirectRoute('customers.show', ['id' => $this->customerId], navigate: true);
    }

    public function render()
    {
        return view('livewire.customers.form')
            ->title($this->customerId ? 'Ubah Pelanggan' : 'Tambah Pelanggan');
    }
}
