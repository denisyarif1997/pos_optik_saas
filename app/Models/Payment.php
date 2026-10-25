<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'sale_id', 'received_by', 'paid_at', 'method', 'amount', 'notes',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'amount' => 'decimal:2'];
    }
}
