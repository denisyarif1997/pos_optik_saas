<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnModel extends Model
{
    use BelongsToStore, HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'store_id', 'sale_id', 'product_id', 'created_by', 'qty',
        'refund_amount', 'item_refund_amount', 'ppn_refund_amount', 'reason', 'status',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
