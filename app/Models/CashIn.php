<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashIn extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = ['store_id', 'created_by', 'received_at', 'category', 'description', 'amount', 'cash_account_id'];

    protected function casts(): array
    {
        return [
            'received_at' => 'date',
            'amount' => 'decimal:2',
            'cash_account_id' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cashAccount(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class, 'cash_account_id');
    }
}
