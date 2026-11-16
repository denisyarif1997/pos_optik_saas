<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'plan_id', 'status', 'started_at', 'ends_at'];

    protected function casts(): array
    {
        return ['started_at' => 'date', 'ends_at' => 'date'];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active'], true) && ! $this->isExpired();
    }
}
