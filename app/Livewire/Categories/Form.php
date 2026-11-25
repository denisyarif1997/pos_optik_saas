<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Form extends Component
{
    public ?int $categoryId = null;

    #[Validate('required|string|max:100')]
    public string $name = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($id) {
            $cat = Category::forStore($storeId)->findOrFail($id);
            $this->authorize('update', $cat);
            $this->categoryId = $cat->id;
            $this->name = $cat->name;
            $this->is_active = (bool) $cat->is_active;
        } else {
            $this->authorize('create', Category::class);
        }
    }

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        if ($this->categoryId) {
            $cat = Category::forStore($storeId)->findOrFail($this->categoryId);
            $this->authorize('update', $cat);
            $cat->update(['name' => $this->name, 'is_active' => $this->is_active]);
            session()->flash('success', 'Kategori diperbarui.');
            $this->dispatch('notify', type: 'success', message: 'Kategori berhasil diperbarui.');
        } else {
            $this->authorize('create', Category::class);
            $slugBase = Str::slug($this->name) ?: 'kategori';
            $slug = $slugBase;
            $i = 1;
            while (Category::forStore($storeId)->where('slug', $slug)->exists()) {
                $slug = $slugBase.'-'.$i++;
            }
            Category::create(['store_id' => $storeId, 'name' => $this->name, 'slug' => $slug, 'is_active' => $this->is_active]);
            session()->flash('success', 'Kategori ditambahkan.');
            $this->dispatch('notify', type: 'success', message: 'Kategori berhasil ditambahkan.');
        }

        $this->redirectRoute('categories.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.categories.form')->title($this->categoryId ? 'Ubah Kategori' : 'Tambah Kategori');
    }
}
