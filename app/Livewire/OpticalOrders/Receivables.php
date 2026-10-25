<?php

namespace App\Livewire\OpticalOrders;

use App\Models\OpticalOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Receivables extends Component
{
    public string $search = '';

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $orders = OpticalOrder::forStore($storeId)
            ->with(['customer'])
            ->where('dp_total', '<', \DB::raw('total'))
            ->when($this->search, function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function ($q) use ($s) {
                    $q->where('code', 'like', $s)
                        ->orWhere('order_number', 'like', $s)
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', $s));
                });
            })
            ->orderBy('promised_date')
            ->get();

        return view('livewire.optical-orders.receivables', compact('orders'))->title('Piutang Pesanan');
    }
}
