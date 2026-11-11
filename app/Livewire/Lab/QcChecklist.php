<?php

namespace App\Livewire\Lab;

use App\Models\LabOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class QcChecklist extends Component
{
    public string $search = '';

    public string $statusFilter = 'diterima';

    public ?int $labOrderId = null;

    public array $qcItems = [];

    public string $qcResult = 'pass';

    public string $qcNotes = '';

    public const QC_STATUSES = [
        'diterima' => 'Diterima Lab (Butuh QC)',
        'siap' => 'Siap (Lulus QC)',
        'selesai' => 'Selesai (QC Lulus)',
    ];

    public const QC_ITEMS = [
        'frame' => 'Kondisi frame sesuai pesanan & tidak ada cacat',
        'lens_od' => 'Lensa OD (sph/cyl/axis) sesuai resep',
        'lens_os' => 'Lensa OS (sph/cyl/axis) sesuai resep',
        'coating' => 'Coating/tint sesuai pesanan (AR/UV/tint)',
        'pd' => 'Pengukuran PD sesuai resep',
        'fitting' => 'Fitting & penyesuaian frame',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function selectOrder(int $labOrderId): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $labOrder = LabOrder::forStore($storeId)->with('order')->findOrFail($labOrderId);

        $this->labOrderId = $labOrderId;
        $this->qcResult = 'pass';
        $this->qcNotes = '';
        $this->qcItems = [];
        foreach (array_keys(self::QC_ITEMS) as $key) {
            $this->qcItems[$key] = true;
        }
    }

    public function submitQc(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->validate([
            'labOrderId' => 'required|integer',
            'qcResult' => 'required|in:pass,fail',
            'qcNotes' => 'required_if:qcResult,fail|nullable|string',
            'qcItems' => 'array',
        ]);

        $labOrder = LabOrder::forStore($storeId)->findOrFail($this->labOrderId);
        $this->authorize('update', $labOrder);

        DB::transaction(function () use ($labOrder) {
            $opticalOrder = $labOrder->order;

            if ($this->qcResult === 'pass') {
                $opticalOrder->update(['status' => 'COMPLETED']);
                session()->flash('success', 'QC lulus. Pesanan optik '.$opticalOrder->order_number.' ditandai selesai.');
            } else {
                $labOrder->update([
                    'status' => 'SALAH',
                    'rejection_reason' => $this->qcNotes ?: 'Gagal QC: '.$this->qcNotes,
                ]);
                $opticalOrder->update(['status' => 'BATAL']);
                session()->flash('warning', 'QC gagal. Lab order ditandai salah dan pesanan dikembalikan ke proses.');
            }
        });

        $this->labOrderId = null;
        $this->qcItems = [];
        $this->qcResult = 'pass';
        $this->qcNotes = '';
    }

    public function cancelQc(): void
    {
        $this->labOrderId = null;
        $this->qcItems = [];
        $this->qcResult = 'pass';
        $this->qcNotes = '';
    }

    public function getSelectedOrderProperty()
    {
        if (! $this->labOrderId) {
            return null;
        }

        $storeId = app('activeStore')->id();

        return LabOrder::forStore($storeId)
            ->with(['order.customer', 'order'])
            ->find($this->labOrderId);
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
            $query->where('status', 'DITERIMA');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('order', fn ($q) => $q->where('order_number', 'like', '%'.$this->search.'%'))
                    ->orWhere('lab_supplier_name', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.lab.qc-checklist', [
            'labOrders' => $query->paginate(12),
            'qcStatuses' => self::QC_STATUSES,
            'qcItems' => self::QC_ITEMS,
            'selectedOrder' => $this->selectedOrder,
        ])->title('QC Checklist Lab');
    }
}
