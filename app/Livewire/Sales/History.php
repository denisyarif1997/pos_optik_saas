<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class History extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.sales.history', [
            'sales' => Sale::forStore($storeId)
                ->with('customer')
                ->when($this->search, fn ($q) => $q->where('code', 'like', '%'.$this->search.'%'))
                ->latest('sold_at')
                ->paginate(12),
        ])->title('Riwayat Transaksi');
    }
}
