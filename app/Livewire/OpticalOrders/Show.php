<?php

namespace App\Livewire\OpticalOrders;

use App\Models\OpticalOrder;
use App\Models\OpticalOrderPayment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $orderId;

    #[Validate('required|string|in:baru,diproses,menunggu_lensa,siap_diambil,selesai,diambil,dibatalkan')]
    public string $status = 'baru';

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->findOrFail($id);
        $this->authorize('view', $order);
        $this->orderId = $order->id;
        $this->status = $order->status;
    }

    public string $payAmount = '';

    public string $payMethod = 'cash';

    public string $payNotes = '';

    public function addPayment(): void
    {
        $this->validate([
            'payAmount' => 'required|numeric|min:1',
            'payMethod' => 'required|string|in:cash,transfer,qris,card',
            'payNotes' => 'nullable|string|max:255',
        ]);

        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->findOrFail($this->orderId);
        $this->authorize('update', $order);

        $amount = (float) $this->payAmount;
        if ($amount > (float) $order->receivable + 0.0001) {
            // allow overpay slightly? no, cap
            $amount = (float) $order->receivable;
        }

        OpticalOrderPayment::create([
            'store_id' => $storeId,
            'optical_order_id' => $order->id,
            'received_by' => auth()->id(),
            'paid_at' => now(),
            'method' => $this->payMethod,
            'amount' => $amount,
            'notes' => $this->payNotes ?: null,
        ]);

        $order->update(['dp_total' => (float) $order->dp_total + $amount]);

        $this->reset(['payAmount', 'payNotes']);
        $this->payMethod = 'cash';

        session()->flash('success', 'Pembayaran dicatat.');
    }

    public function updateStatus(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->findOrFail($this->orderId);
        $this->authorize('update', $order);

        $data = ['status' => $this->status];
        if ($this->status === 'diambil' || $this->status === 'selesai') {
            $data['taken_at'] = now()->toDateString();
        }

        $order->update($data);
        session()->flash('success', 'Status pesanan diperbarui.');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->with(['customer', 'items', 'payments.receiver'])->findOrFail($this->orderId);

        return view('livewire.optical-orders.show', compact('order'))->title('Pesanan '.$order->code);
    }
}
