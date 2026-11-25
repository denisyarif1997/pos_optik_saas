<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OpticalOrder extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'customer_id', 'prescription_id', 'sale_id', 'order_number',
        'code', 'lab_name', 'prescription_snapshot', 'ordered_at',
        'estimated_done_at', 'promised_date', 'taken_at',
        'subtotal', 'discount_total', 'discount_percent', 'discount_amount', 'ppn_rate', 'ppn_amount',
        'total', 'dp_total', 'status', 'work_notes',
    ];

    protected function casts(): array
    {
        return [
            'ordered_at' => 'date',
            'estimated_done_at' => 'date',
            'promised_date' => 'date',
            'taken_at' => 'date',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'dp_total' => 'decimal:2',
            'prescription_snapshot' => 'array',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OpticalOrderItem::class);
    }

    public function labOrder(): HasOne
    {
        return $this->hasOne(LabOrder::class, 'order_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OpticalOrderPayment::class, 'optical_order_id');
    }

    public function getReceivableAttribute(): float
    {
        return (float) $this->total - (float) $this->dp_total;
    }
}
