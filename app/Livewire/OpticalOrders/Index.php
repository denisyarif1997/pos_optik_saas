<?php

namespace App\Livewire\OpticalOrders;

use App\Models\OpticalOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.optical-orders.index', [
            'orders' => OpticalOrder::forStore($storeId)
                ->with('customer')
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->latest()
                ->paginate(12),
        ])->title('Pesanan Kacamata');
    }
}
