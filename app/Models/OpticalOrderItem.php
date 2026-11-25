<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpticalOrderItem extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'optical_order_id', 'product_id',
        'item_type', 'description', 'qty', 'price', 'discount', 'ppn_rate', 'ppn_amount', 'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }
}
