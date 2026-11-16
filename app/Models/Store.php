<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(static function (Store $store): void {
            CashAccount::ensureDefaultsForStore($store);
        });
    }

    protected $fillable = [
        'name', 'slug', 'phone', 'address', 'logo_path', 'receipt_note', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_user')
            ->withPivot(['role', 'is_active'])
            ->withTimestamps();
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('is_active', true);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription(): ?Subscription
    {
        return $this->subscriptions()->latest('id')->first();
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function cashAccounts(): HasMany
    {
        return $this->hasMany(CashAccount::class);
    }
}
