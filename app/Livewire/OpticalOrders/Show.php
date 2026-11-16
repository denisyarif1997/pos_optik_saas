<?php

namespace App\Livewire\OpticalOrders;

use App\Models\CashAccount;
use App\Models\OpticalOrder;
use App\Models\OpticalOrderPayment;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $orderId;

    #[Validate('required|string|in:DRAFT,PESANAN,DIPROSES,SIAP,COMPLETED,BATAL')]
    public string $status = 'DRAFT';

    public string $statusMessage = '';

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->findOrFail($id);
        $this->authorize('view', $order);
        $this->orderId = $order->id;
        $this->status = $order->status;
        $this->statusMessage = session('status_message', '');
        $this->cashAccountId = CashAccount::forStore($storeId)
            ->where('is_active', true)
            ->orderBy('id')
            ->value('id');
        $this->canReceivePayment = in_array(app('activeStore')->role(), ['owner', 'kasir'], true);
    }

    public string $payAmount = '';

    public string $payMethod = 'cash';

    public ?int $cashAccountId = null;

    public string $payNotes = '';

    public bool $canReceivePayment = false;

    public function addPayment(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $order = OpticalOrder::forStore($storeId)->findOrFail($this->orderId);
        $this->authorize('receivePayment', $order);

        $this->validate([
            'payAmount' => 'required|numeric|min:1',
            'payMethod' => 'required|string|in:cash,transfer,qris,card',
            'cashAccountId' => [
                'required',
                'integer',
                Rule::exists('cash_accounts', 'id')
                    ->where(fn ($query) => $query->where('store_id', $storeId)->where('is_active', true)),
            ],
            'payNotes' => 'nullable|string|max:255',
        ]);

        $amount = (float) $this->payAmount;
        if ($amount > (float) $order->receivable + 0.0001) {
            // allow overpay slightly? no, cap
            $amount = (float) $order->receivable;
        }

        OpticalOrderPayment::create([
            'store_id' => $storeId,
            'optical_order_id' => $order->id,
            'received_by' => auth()->id(),
            'cash_account_id' => $this->cashAccountId,
            'paid_at' => now(),
            'method' => $this->payMethod,
            'amount' => $amount,
            'notes' => $this->payNotes ?: null,
        ]);

        $order->update(['dp_total' => (float) $order->dp_total + $amount]);

        $this->reset(['payAmount', 'payNotes']);
        $this->payMethod = 'cash';

        $this->statusMessage = 'Pembayaran dicatat.';
    }

    public function updateStatus(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->findOrFail($this->orderId);
        $this->authorize('update', $order);

        $data = ['status' => $this->status];
        if ($this->status === 'SIAP' || $this->status === 'COMPLETED') {
            $data['taken_at'] = now()->toDateString();
        }

        $order->update($data);

        $this->statusMessage = 'Status pesanan diperbarui.';
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $order = OpticalOrder::forStore($storeId)->with(['customer', 'items', 'payments.receiver', 'payments.cashAccount'])->findOrFail($this->orderId);

        return view('livewire.optical-orders.show', [
            'order' => $order,
            'cashAccounts' => CashAccount::forStore($storeId)->where('is_active', true)->orderBy('name')->get(),
        ])->title('Pesanan '.$order->code);
    }
}
