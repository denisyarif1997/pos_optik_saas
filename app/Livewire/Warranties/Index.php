<?php

namespace App\Livewire\Warranties;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Warranty;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Validate('required|integer')]
    public ?int $customer_id = null;

    #[Validate('nullable|integer|exists:sales,id')]
    public ?int $sale_id = null;

    public string $sale_code = '';

    #[Validate('nullable|date')]
    public ?string $valid_until = null;

    #[Validate('nullable|string')]
    public string $claim_notes = '';

    public ?Sale $sale = null;

    public function nextCode(int $storeId): string
    {
        $nextNumber = $this->getNextNumber($storeId) + 1;

        return 'GRS-'.now()->format('Ymd').'-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function getNextNumber(int $storeId): int
    {
        $id = Warranty::forStore($storeId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->value('id');

        return $id > 0 ? (int) substr((string) $id, -4) : 0;
    }

    public function findSale(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $this->sale = null;
        $this->sale_id = null;

        $sale = Sale::forStore($storeId)->where('code', trim($this->sale_code))->first();
        if (! $sale) {
            $this->addError('sale_code', 'Nota tidak ditemukan diTokomu.');

            return;
        }

        $this->sale = $sale;
        $this->sale_id = $sale->id;

        if (! $this->customer_id) {
            $this->customer_id = $sale->customer_id;
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $customer = Customer::forStore($storeId)->findOrFail($this->customer_id);
        $sale = $this->sale_id ? Sale::forStore($storeId)->findOrFail($this->sale_id) : null;

        Warranty::create([
            'store_id' => $storeId,
            'sale_id' => $this->sale_id,
            'customer_id' => $customer->id,
            'code' => $this->nextCode($storeId),
            'valid_until' => $this->valid_until ?: now()->addMonths(3)->toDateString(),
            'claim_notes' => $this->claim_notes ?: null,
            'status' => 'active',
        ]);

        $this->reset(['sale_id', 'sale_code', 'sale', 'valid_until', 'claim_notes']);
        session()->flash('success', 'Garansi diterbitkan.');
    }

    public function claim(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $w = Warranty::forStore($storeId)->findOrFail($id);
        $w->update(['status' => 'claimed']);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.warranties.index', [
            'customers' => Customer::forStore($storeId)->where('is_active', true)->orderBy('name')->limit(200)->get(),
            'warranties' => Warranty::forStore($storeId)->with('sale')->latest()->limit(20)->get(),
            'sale' => $this->sale,
        ])->title('Garansi');
    }
}
