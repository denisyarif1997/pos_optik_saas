<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'price', 'duration_days', 'features', 'is_active'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'features' => 'array', 'is_active' => 'boolean'];
    }
}
