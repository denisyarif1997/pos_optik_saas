<?php

namespace App\Livewire\Lab;

use App\Models\LabOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class LabOrderTracker extends Component
{
    public string $search = '';

    public string $statusFilter = 'all';

    public ?int $orderId = null;

    public string $newStatus = 'MENUNGGU';

    public ?string $promisedDate = null;

    public string $rejectionReason = '';

    public const STATUSES = [
        'MENUNGGU' => 'Menunggu',
        'DIPESAN' => 'Dipesan',
        'DITERIMA' => 'Diterima',
        'SALAH' => 'Salah',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function selectOrder(int $orderId): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $labOrder = LabOrder::forStore($storeId)->with('order')->findOrFail($orderId);

        $this->orderId = $labOrder->id;
        $this->newStatus = $labOrder->status;
        $this->promisedDate = $labOrder->expected_completion_date?->format('Y-m-d');
        $this->rejectionReason = $labOrder->rejection_reason ?? '';
    }

    public function updateStatus(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->validate([
            'orderId' => 'required|integer',
            'newStatus' => 'required|in:MENUNGGU,DIPESAN,DITERIMA,SALAH',
            'promisedDate' => 'nullable|date',
            'rejectionReason' => 'required_if:newStatus,SALAH',
        ]);

        $labOrder = LabOrder::forStore($storeId)->findOrFail($this->orderId);
        $this->authorize('update', $labOrder);

        DB::transaction(function () use ($labOrder) {
            $labOrder->update([
                'status' => $this->newStatus,
                'expected_completion_date' => $this->promisedDate ?: null,
                'rejection_reason' => $this->newStatus === 'SALAH' ? $this->rejectionReason : null,
            ]);

            $opticalOrder = $labOrder->order;
            if ($this->newStatus === 'DITERIMA') {
                $opticalOrder->update(['status' => 'SIAP', 'taken_at' => now()->toDateString()]);
            } elseif ($this->newStatus === 'DIPESAN') {
                $opticalOrder->update(['status' => 'DIPROSES']);
            } elseif ($this->newStatus === 'SALAH') {
                $opticalOrder->update(['status' => 'PESANAN']);
            } else {
                $opticalOrder->update(['status' => 'DRAFT']);
            }
        });

        session()->flash('success', 'Status lab order diperbarui.');
        $this->orderId = null;
        $this->newStatus = 'MENUNGGU';
        $this->promisedDate = null;
        $this->rejectionReason = '';
    }

    public function getSelectedOrderProperty()
    {
        if (! $this->orderId) {
            return null;
        }

        $storeId = app('activeStore')->id();

        return LabOrder::forStore($storeId)
            ->with(['order.customer', 'order'])
            ->find($this->orderId);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->authorize('viewAny', LabOrder::class);

        $query = LabOrder::forStore($storeId)
            ->with(['order.customer', 'order'])
            ->latest('created_at');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('order', fn ($q) => $q->where('order_number', 'like', '%'.$this->search.'%'))
                ->orWhere('lab_supplier_name', 'like', '%'.$this->search.'%');
        }

        return view('livewire.lab.lab-order-tracker', [
            'labOrders' => $query->paginate(12),
            'statuses' => self::STATUSES,
            'selectedOrder' => $this->selectedOrder,
        ])->title('Tracker Lab');
    }
}
