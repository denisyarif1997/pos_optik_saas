<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $customerId;

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($id);
        $this->authorize('view', $customer);
        $this->customerId = $customer->id;
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)
            ->with(['prescriptions' => fn ($q) => $q->latest(), 'sales' => fn ($q) => $q->latest()->limit(10)])
            ->findOrFail($this->customerId);

        return view('livewire.customers.show', compact('customer'))->title($customer->name);
    }
}
