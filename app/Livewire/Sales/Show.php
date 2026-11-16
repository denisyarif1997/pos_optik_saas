<?php

namespace App\Livewire\Sales;

use App\Models\CashAccount;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $saleId;

    public float $amount = 0;

    public string $method = 'cash';

    public ?int $cashAccountId = null;

    public bool $canReceivePayment = false;

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->findOrFail($id);
        $this->authorize('view', $sale);
        $this->saleId = $sale->id;
        $this->cashAccountId = CashAccount::forStore($storeId)
            ->where('is_active', true)
            ->orderBy('id')
            ->value('id');
        $this->canReceivePayment = in_array(app('activeStore')->role(), ['owner', 'kasir'], true);
    }

    public function addPayment(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $sale = Sale::forStore($storeId)->findOrFail($this->saleId);
        $this->authorize('receivePayment', $sale);

        $this->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'string', 'in:cash,transfer,qris,card'],
            'cashAccountId' => [
                'required',
                'integer',
                Rule::exists('cash_accounts', 'id')
                    ->where(fn ($query) => $query->where('store_id', $storeId)->where('is_active', true)),
            ],
        ]);

        DB::transaction(function () use ($sale, $storeId) {
            Payment::create([
                'store_id' => $storeId,
                'sale_id' => $sale->id,
                'received_by' => auth()->id(),
                'cash_account_id' => $this->cashAccountId,
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

        $sale = Sale::forStore($storeId)->with(['items.product', 'payments.cashAccount', 'customer'])->findOrFail($this->saleId);

        return view('livewire.sales.show', [
            'sale' => $sale,
            'cashAccounts' => CashAccount::forStore($storeId)->where('is_active', true)->orderBy('name')->get(),
        ])->title('Nota '.$sale->code);
    }
}
