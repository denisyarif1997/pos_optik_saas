<?php

namespace App\Livewire\Settings;

use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class StoreSettings extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public ?string $phone = null;

    #[Validate('nullable|string|max:500')]
    public ?string $address = null;

    #[Validate('nullable|string|max:500')]
    public ?string $receipt_note = null;

    public $logo = null; // UploadedFile

    public ?string $existingLogo = null;

    public function mount(): void
    {
        $store = $this->store();
        abort_unless($store, 403);

        $this->name = $store->name;
        $this->phone = $store->phone;
        $this->address = $store->address;
        $this->receipt_note = $store->receipt_note;
        $this->existingLogo = $store->logo_path;
    }

    public function save(): void
    {
        $this->validate();

        $store = $this->store();
        abort_unless($store, 403);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'receipt_note' => $this->receipt_note,
        ];

        if ($this->logo) {
            $this->validate(['logo' => 'image|max:2048']);
            if ($store->logo_path) {
                Storage::disk('public')->delete($store->logo_path);
            }
            $path = $this->logo->store('logos', 'public');
            $data['logo_path'] = $path;
            $this->existingLogo = $path;
        }

        $store->update($data);
        $this->logo = null;

        session()->flash('success', 'Pengaturan toko disimpan.');
        $this->dispatch('notify', type: 'success', message: 'Pengaturan toko disimpan.');
    }

    public function removeLogo(): void
    {
        $store = $this->store();
        abort_unless($store, 403);

        if ($store->logo_path) {
            Storage::disk('public')->delete($store->logo_path);
            $store->update(['logo_path' => null]);
            $this->existingLogo = null;
        }

        session()->flash('success', 'Logo dihapus.');
        $this->dispatch('notify', type: 'success', message: 'Logo dihapus.');
    }

    protected function store(): ?Store
    {
        return app('activeStore')->get();
    }

    public function render()
    {
        abort_unless($this->store(), 403);

        return view('livewire.settings.store-settings')->title('Pengaturan Toko');
    }
}
