<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashAccount extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = ['store_id', 'code', 'name', 'type', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function ensureDefaultsForStore(Store $store): void
    {
        foreach ([
            ['code' => 'kas-toko', 'name' => 'Kas Toko', 'type' => 'cash'],
            ['code' => 'kas-bank', 'name' => 'Kas Bank', 'type' => 'bank'],
        ] as $account) {
            self::query()->firstOrCreate(
                ['store_id' => $store->id, 'code' => $account['code']],
                ['name' => $account['name'], 'type' => $account['type'], 'is_active' => true],
            );
        }
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function opticalOrderPayments(): HasMany
    {
        return $this->hasMany(OpticalOrderPayment::class);
    }

    public function cashIns(): HasMany
    {
        return $this->hasMany(CashIn::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
