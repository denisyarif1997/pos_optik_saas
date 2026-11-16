<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'sale_id', 'product_id', 'qty', 'price', 'discount', 'subtotal',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'discount' => 'decimal:2', 'subtotal' => 'decimal:2'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
