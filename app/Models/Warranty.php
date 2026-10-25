<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = ['store_id', 'sale_id', 'optical_order_id', 'customer_id', 'code', 'valid_until', 'claim_notes', 'status'];

    protected function casts(): array
    {
        return ['valid_until' => 'date'];
    }
}
