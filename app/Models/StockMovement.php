<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'product_id', 'type', 'qty',
        'stock_before', 'stock_after',
        'reference_type', 'reference_id', 'created_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function referenceLabel(): string
    {
        return match ($this->reference_type) {
            Sale::class => 'Penjualan',
            Purchase::class => 'Pembelian',
            ReturnModel::class => 'Retur',
            StockOpname::class => 'Stock Opname',
            default => $this->reference_type ? class_basename($this->reference_type) : 'Manual',
        };
    }
}
