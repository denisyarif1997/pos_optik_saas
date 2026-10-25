<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id', 'customer_id', 'created_by', 'checked_at',
        'od_sph', 'od_cyl', 'od_axis', 'od_add',
        'os_sph', 'os_cyl', 'os_axis', 'os_add',
        'pd', 'notes', 'attachment_path',
    ];

    protected function casts(): array
    {
        return ['checked_at' => 'date'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
