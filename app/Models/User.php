<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_user')
            ->withPivot(['role', 'is_active'])
            ->withTimestamps();
    }

    public function activeStores(): BelongsToMany
    {
        return $this->stores()->wherePivot('is_active', true);
    }

    public function store(): ?Store
    {
        return $this->activeStores()->first();
    }

    public function storeId(): ?int
    {
        return $this->store()?->id;
    }

    public function canOwnAnotherStore(): bool
    {
        return $this->stores()->count() === 0;
    }

    public function belongsToStore(int $storeId): bool
    {
        return $this->activeStores()->where('stores.id', $storeId)->exists();
    }

    public function roleInStore(int $storeId): ?string
    {
        $store = $this->activeStores()->where('stores.id', $storeId)->first();

        return $store?->pivot->role;
    }
}
