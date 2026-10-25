<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabOrder extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'order_id', 'lab_supplier_name', 'status', 'lab_cost',
        'rejection_reason', 'expected_completion_date',
    ];

    protected function casts(): array
    {
        return [
            'lab_cost' => 'decimal:2',
            'expected_completion_date' => 'date',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OpticalOrder::class, 'order_id');
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
