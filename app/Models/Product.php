<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'category_id', 'type', 'sku', 'barcode', 'name',
        'brand', 'model', 'color', 'size', 'unit',
        'cost_price', 'sell_price', 'stock', 'min_stock',
        'lens_attributes', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
            'lens_attributes' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
