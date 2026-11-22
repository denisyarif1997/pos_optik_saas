<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'purchase_id', 'product_id', 'sph', 'cyl', 'add_val', 'qty', 'buy_price', 'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'buy_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'sph' => 'decimal:2',
            'cyl' => 'decimal:2',
            'add_val' => 'decimal:2',
        ];
    }
}
