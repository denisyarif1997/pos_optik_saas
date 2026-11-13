<?php

namespace App\Livewire\StockOpnames;

use App\Models\StockOpname;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public int $opnameId;

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = StockOpname::forStore($storeId)->findOrFail($id);
        $this->opnameId = $opname->id;
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = StockOpname::forStore($storeId)
            ->with(['items.product', 'conductor'])
            ->findOrFail($this->opnameId);

        return view('livewire.stock-opnames.show', compact('opname'))->title('Detail Stock Opname '.$opname->code);
    }
}
