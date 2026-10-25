<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Validate('required|string|max:100')]
    public string $name = '';

    public bool $showForm = false;

    public function save(): void
    {
        $this->validate();
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $slugBase = Str::slug($this->name) ?: 'kategori';
        $slug = $slugBase;
        $i = 1;
        while (Category::forStore($storeId)->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i++;
        }

        Category::create(['store_id' => $storeId, 'name' => $this->name, 'slug' => $slug, 'is_active' => true]);
        $this->reset('name');
        session()->flash('success', 'Kategori ditambahkan.');
    }

    public function toggle(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $cat = Category::forStore($storeId)->findOrFail($id);
        $cat->update(['is_active' => ! $cat->is_active]);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.categories.index', [
            'categories' => Category::forStore($storeId)->orderBy('name')->get(),
        ])->title('Kategori');
    }
}
