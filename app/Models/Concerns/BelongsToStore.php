<?php

namespace App\Models\Concerns;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStore
{
    protected static function bootBelongsToStore(): void
    {
        static::creating(function ($model) {
            if (empty($model->store_id) && app('activeStore')->id()) {
                $model->store_id = app('activeStore')->id();
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeForActiveStore(Builder $query, ?int $storeId = null): Builder
    {
        $storeId ??= app('activeStore')->id();

        abort_unless($storeId, 403, 'Toko aktif belum dipilih.');

        return $query->where($query->getModel()->getTable().'.store_id', $storeId);
    }

    public function scopeForStore(Builder $query, int $storeId): Builder
    {
        return $query->where($query->getModel()->getTable().'.store_id', $storeId);
    }
}
