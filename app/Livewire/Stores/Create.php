<?php

namespace App\Livewire\Stores;

use App\Models\Store;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Create extends Component
{
    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('nullable|string')]
    public string $address = '';

    public function save(): void
    {
        if (app('activeStore')->check(auth()->user())) {
            $this->redirectRoute('dashboard', navigate: true);

            return;
        }

        $this->validate();

        $slugBase = Str::slug($this->name) ?: 'toko';
        $slug = $slugBase;
        $counter = 1;
        while (Store::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$counter++;
        }

        $store = Store::create([
            'name' => $this->name,
            'slug' => $slug,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
        ]);

        $store->members()->attach(auth()->id(), ['role' => 'owner', 'is_active' => true]);

        session()->flash('success', 'Toko berhasil dibuat.');
        $this->dispatch('notify', type: 'success', message: 'Toko berhasil dibuat.');

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.stores.create', [
            'store' => app('activeStore')->get(auth()->user()),
        ])->title('Profil Toko');
    }
}
