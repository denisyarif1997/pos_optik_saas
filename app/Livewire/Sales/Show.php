<?php

namespace App\Livewire\Sales;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $saleId;

    #[Validate('required|numeric|min:1')]
    public float $amount = 0;

    public string $method = 'cash';

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->findOrFail($id);
        $this->authorize('view', $sale);
        $this->saleId = $sale->id;
    }

    public function addPayment(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->findOrFail($this->saleId);
        $this->authorize('view', $sale);

        DB::transaction(function () use ($sale, $storeId) {
            Payment::create([
                'store_id' => $storeId,
                'sale_id' => $sale->id,
                'received_by' => auth()->id(),
                'paid_at' => now(),
                'method' => $this->method,
                'amount' => $this->amount,
            ]);

            $paid = (float) $sale->payments()->sum('amount');
            $sale->update([
                'paid_total' => $paid,
                'payment_status' => $paid >= (float) $sale->total ? 'lunas' : ($paid > 0 ? 'dp' : 'belum_bayar'),
            ]);
        });

        $this->reset('amount');
        session()->flash('success', 'Pembayaran tercatat.');
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->with(['items.product', 'payments', 'customer'])->findOrFail($this->saleId);

        return view('livewire.sales.show', compact('sale'))->title('Nota '.$sale->code);
    }
}
