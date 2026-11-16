<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = ['store_id', 'created_by', 'spent_at', 'category', 'description', 'amount'];

    protected function casts(): array
    {
        return ['spent_at' => 'date', 'amount' => 'decimal:2'];
    }
}
