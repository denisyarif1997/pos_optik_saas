<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLensMatrix extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'product_id', 'sph', 'cyl', 'add_val', 'stock_qty', 'min_stock_alert',
    ];

    protected function casts(): array
    {
        return [
            'sph' => 'decimal:2',
            'cyl' => 'decimal:2',
            'add_val' => 'decimal:2',
            'stock_qty' => 'integer',
            'min_stock_alert' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock_qty', '<=', 'min_stock_alert');
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock_qty', 0);
    }
}
