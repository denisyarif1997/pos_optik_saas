<?php

namespace App\Livewire\Prescriptions;

use App\Models\Customer;
use App\Models\Prescription;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public int $customerId;

    #[Validate('nullable|date')]
    public ?string $checked_at = null;

    public ?float $od_sph = null;

    public ?float $od_cyl = null;

    public ?int $od_axis = null;

    public ?float $od_add = null;

    public ?float $os_sph = null;

    public ?float $os_cyl = null;

    public ?int $os_axis = null;

    public ?float $os_add = null;

    public ?float $pd = null;

    #[Validate('nullable|string')]
    public string $notes = '';

    public function mount(int $customerId): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($customerId);
        $this->authorize('update', $customer);
        $this->customerId = $customer->id;
        $this->checked_at = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($this->customerId);
        $this->authorize('update', $customer);

        Prescription::create([
            'store_id' => $storeId,
            'customer_id' => $customer->id,
            'created_by' => auth()->id(),
            'checked_at' => $this->checked_at ?: null,
            'od_sph' => $this->od_sph,
            'od_cyl' => $this->od_cyl,
            'od_axis' => $this->od_axis,
            'od_add' => $this->od_add,
            'os_sph' => $this->os_sph,
            'os_cyl' => $this->os_cyl,
            'os_axis' => $this->os_axis,
            'os_add' => $this->os_add,
            'pd' => $this->pd,
            'notes' => $this->notes ?: null,
        ]);

        $this->redirectRoute('customers.show', ['id' => $customer->id], navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.prescriptions.form', [
            'customer' => Customer::forStore($storeId)->findOrFail($this->customerId),
        ])->title('Tambah Resep');
    }
}
