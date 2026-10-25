<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($id);
        $this->authorize('delete', $customer);
        $customer->update(['is_active' => false]);

        session()->flash('success', 'Pelanggan dinonaktifkan.');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.customers.index', [
            'customers' => Customer::forStore($storeId)
                ->when($this->search, fn ($q) => $q->where(function ($w) {
                    $w->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                }))
                ->latest()
                ->paginate(12),
        ])->title('Pelanggan');
    }
}
