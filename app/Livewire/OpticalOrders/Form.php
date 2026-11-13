<?php

namespace App\Livewire\OpticalOrders;

use App\Models\Customer;
use App\Models\OpticalOrder;
use App\Models\Prescription;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $orderId = null;

    #[Validate('required|integer')]
    public ?int $customer_id = null;

    #[Validate('nullable|integer')]
    public ?int $prescription_id = null;

    #[Validate('nullable|string|max:100')]
    public string $lab_name = '';

    #[Validate('nullable|date')]
    public ?string $estimated_done_at = null;

    #[Validate('required|numeric|min:0')]
    public float $total = 0;

    #[Validate('required|numeric|min:0')]
    public float $dp_total = 0;

    public array $frameIds = [];

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $order = OpticalOrder::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $order);
            $this->orderId = $order->id;
            $this->customer_id = $order->customer_id;
            $this->prescription_id = $order->prescription_id;
            $this->lab_name = $order->lab_name ?? '';
            $this->estimated_done_at = $order->estimated_done_at?->format('Y-m-d');
            $this->total = (float) $order->total;
            $this->dp_total = (float) $order->dp_total;
        } else {
            $this->authorize('create', OpticalOrder::class);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($this->customer_id);

        if ($this->prescription_id) {
            Prescription::forStore($storeId)->where('customer_id', $customer->id)->findOrFail($this->prescription_id);
        }

        DB::transaction(function () use ($storeId) {
            $code = 'OPT-'.now()->format('Ymd').'-'.str_pad((string) ($this->getNextNumber($storeId) + 1), 4, '0', STR_PAD_LEFT);

            if ($this->orderId) {
                $order = OpticalOrder::forStore($storeId)->findOrFail($this->orderId);
                $this->authorize('update', $order);
                $order->update([
                    'customer_id' => $this->customer_id,
                    'prescription_id' => $this->prescription_id,
                    'lab_name' => $this->lab_name ?: null,
                    'estimated_done_at' => $this->estimated_done_at ?: null,
                    'total' => $this->total,
                    'dp_total' => $this->dp_total,
                ]);
            } else {
                $this->authorize('create', OpticalOrder::class);
                $order = OpticalOrder::create([
                    'store_id' => $storeId,
                    'customer_id' => $this->customer_id,
                    'prescription_id' => $this->prescription_id,
                    'code' => $code,
                    'lab_name' => $this->lab_name ?: null,
                    'ordered_at' => now()->toDateString(),
                    'estimated_done_at' => $this->estimated_done_at ?: null,
                    'total' => $this->total,
                    'dp_total' => $this->dp_total,
                    'status' => 'DRAFT',
                ]);

                foreach ($this->frameIds as $pid) {
                    $product = Product::forStore($storeId)->find($pid);
                    if (! $product) {
                        continue;
                    }
                    $order->items()->create([
                        'store_id' => $storeId,
                        'product_id' => $product->id,
                        'item_type' => $product->type,
                        'description' => $product->name,
                        'qty' => 1,
                        'price' => $product->sell_price,
                        'subtotal' => $product->sell_price,
                    ]);
                }
            }
        });

        $this->redirectRoute('optical-orders.index', navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.optical-orders.form', [
            'customers' => Customer::forStore($storeId)->where('is_active', true)->orderBy('name')->limit(200)->get(),
            'frames' => Product::forStore($storeId)->where('is_active', true)->where('type', 'FRAME')->orderBy('name')->limit(100)->get(),
        ])->title($this->orderId ? 'Ubah Pesanan' : 'Pesanan Baru');
    }
}
