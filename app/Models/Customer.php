<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'name', 'phone', 'address', 'birth_date', 'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'is_active' => 'boolean'];
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
