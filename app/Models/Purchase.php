<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = ['store_id', 'supplier_id', 'code', 'purchased_at', 'total', 'status', 'notes'];

    protected function casts(): array
    {
        return ['purchased_at' => 'date', 'total' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
