<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'product_id', 'type', 'qty',
        'stock_before', 'stock_after',
        'reference_type', 'reference_id', 'created_by', 'notes',
    ];
}
