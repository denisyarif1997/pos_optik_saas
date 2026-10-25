<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use BelongsToStore, HasFactory;

    protected $table = 'returns';

    protected $fillable = ['store_id', 'sale_id', 'product_id', 'created_by', 'qty', 'refund_amount', 'reason', 'status'];
}
