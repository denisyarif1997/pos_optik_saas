<?php

namespace App\Livewire\Returns;

use App\Models\ReturnModel;
use App\Models\Sale;
use App\Services\ReturnService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public string $code = '';

    public ?int $sale_id = null;

    public ?int $product_id = null;

    #[Validate('required|integer|min:1')]
    public int $qty = 1;

    public string $reason = '';

    public function findSale(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = Sale::forStore($storeId)->with('items.product')->where('code', $this->code)->first();
        if (! $sale) {
            $this->addError('code', 'Nota tidak ditemukan di tokomu.');

            return;
        }
        $this->sale_id = $sale->id;
    }

    public function save(ReturnService $returns): void
    {
        $this->validate();
        $this->authorize('create', ReturnModel::class);

        $returns->create(auth()->user(), $this->sale_id, $this->product_id, $this->qty, $this->reason ?: null);
        session()->flash('success', 'Retur tersimpan, stok kembali bertambah.');
        $this->reset(['sale_id', 'product_id', 'qty', 'reason']);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $sale = $this->sale_id ? Sale::forStore($storeId)->with('items.product')->find($this->sale_id) : null;

        return view('livewire.returns.index', [
            'sale' => $sale,
            'history' => ReturnModel::forStore($storeId)->with('sale')->latest()->limit(20)->get(),
        ])->title('Retur');
    }
}
