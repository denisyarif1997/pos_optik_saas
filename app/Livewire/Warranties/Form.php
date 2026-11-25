<?php

namespace App\Livewire\Warranties;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Warranty;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $warrantyId = null;

    #[Validate('required|integer')]
    public ?int $customer_id = null;

    #[Validate('required|integer|exists:sales,id')]
    public ?int $sale_id = null;

    #[Validate('nullable|date')]
    public ?string $valid_until = null;

    #[Validate('nullable|string')]
    public string $claim_notes = '';

    #[Validate('required|string|in:active,claimed,expired')]
    public string $status = 'active';

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $w = Warranty::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $w);
            $this->warrantyId = $w->id;
            $this->customer_id = $w->customer_id;
            $this->sale_id = $w->sale_id;
            $this->valid_until = $w->valid_until?->format('Y-m-d');
            $this->claim_notes = $w->claim_notes ?? '';
            $this->status = $w->status;
        } else {
            $this->authorize('create', Warranty::class);
            $this->valid_until = now()->addMonths(3)->toDateString();
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $data = [
            'customer_id' => $this->customer_id,
            'sale_id' => $this->sale_id,
            'valid_until' => $this->valid_until ?: now()->addMonths(3)->toDateString(),
            'claim_notes' => $this->claim_notes ?: null,
            'status' => $this->status,
        ];

        if ($this->warrantyId) {
            $w = Warranty::forStore($storeId)->findOrFail($this->warrantyId);
            $this->authorize('update', $w);
            $w->update($data);
        } else {
            $this->authorize('create', Warranty::class);
            $customer = Customer::forStore($storeId)->findOrFail($this->customer_id);
            Sale::forStore($storeId)->findOrFail($this->sale_id);
            $data['store_id'] = $storeId;
            $data['code'] = 'GRS-'.now()->format('Ymd').'-'.str_pad((string) (Warranty::forStore($storeId)->whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            Warranty::create($data);
        }

        session()->flash('success', $this->warrantyId ? 'Garansi diperbarui.' : 'Garansi ditambahkan.');
        $this->dispatch('notify', type: 'success', message: $this->warrantyId ? 'Garansi berhasil diperbarui.' : 'Garansi berhasil ditambahkan.');

        $this->redirectRoute('warranties.index', navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.warranties.form', [
            'customers' => Customer::forStore($storeId)->where('is_active', true)->orderBy('name')->limit(200)->get(),
        ])->title($this->warrantyId ? 'Ubah Garansi' : 'Tambah Garansi');
    }
}
