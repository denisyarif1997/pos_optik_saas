<?php

namespace App\Livewire\StockOpnames;

use App\Models\StockOpname;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Count extends Component
{
    public int $opnameId;

    public array $counts = [];

    public function mount(int $id): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = StockOpname::forStore($storeId)->findOrFail($id);
        if ($opname->status !== 'draft') {
            abort(403, 'Opname sudah selesai.');
        }
        $this->opnameId = $opname->id;

        foreach ($opname->items as $it) {
            $this->counts[$it->id] = $it->counted_qty ?? $it->system_qty;
        }
    }

    public function finalize(StockService $stocks): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = StockOpname::forStore($storeId)->findOrFail($this->opnameId);
        abort_unless($opname->status === 'draft', 403);

        DB::transaction(function () use ($opname, $stocks) {
            foreach ($opname->items as $item) {
                $counted = (int) ($this->counts[$item->id] ?? $item->system_qty);
                $diff = $counted - (int) $item->system_qty;
                $item->update(['counted_qty' => $counted, 'diff' => $diff]);

                if ($diff !== 0) {
                    $stocks->adjust(auth()->user(), $item->product_id, $diff, 'opname', 'Stock opname '.$opname->code);
                }
            }
            $opname->update(['status' => 'completed', 'completed_at' => now()]);
        });

        session()->flash('success', 'Stock opname selesai, stok disesuaikan.');
        $this->dispatch('notify', type: 'success', message: 'Stock opname selesai, stok disesuaikan.');
        $this->redirectRoute('stock-opnames.index', navigate: true);
    }

    public function cancel(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);
        $opname = StockOpname::forStore($storeId)->findOrFail($this->opnameId);
        $opname->update(['status' => 'cancelled']);
        $this->redirectRoute('stock-opnames.index', navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = StockOpname::forStore($storeId)->with(['items.product'])->findOrFail($this->opnameId);

        return view('livewire.stock-opnames.count', compact('opname'))->title('Hitung Opname '.$opname->code);
    }
}
