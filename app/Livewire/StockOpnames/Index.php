<?php

namespace App\Livewire\StockOpnames;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function create(): void
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        $opname = DB::transaction(function () use ($storeId) {
            $code = 'OPN-'.now()->format('Ymd').'-'.str_pad((string) (StockOpname::forStore($storeId)->whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            $opname = StockOpname::create([
                'store_id' => $storeId,
                'conducted_by' => auth()->id(),
                'code' => $code,
                'conducted_at' => now()->toDateString(),
                'status' => 'draft',
            ]);
            foreach (Product::forStore($storeId)->where('is_active', true)->get() as $p) {
                StockOpnameItem::create([
                    'store_id' => $storeId,
                    'stock_opname_id' => $opname->id,
                    'product_id' => $p->id,
                    'system_qty' => (int) $p->stock,
                ]);
            }

            return $opname;
        });

        $this->redirectRoute('stock-opnames.count', ['id' => $opname->id], navigate: true);
    }

    public function render()
    {
        $storeId = app('activeStore')->id();
        abort_unless($storeId, 403);

        return view('livewire.stock-opnames.index', [
            'opnames' => StockOpname::forStore($storeId)->latest()->limit(30)->get(),
        ])->title('Stock Opname');
    }
}
