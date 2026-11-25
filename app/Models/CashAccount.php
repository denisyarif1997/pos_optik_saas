<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where($query->getModel()->getTable().'.is_active', true);
    }

    /**
     * Active cash accounts for a store, keyed by id and labelled by name.
     *
     * @return array<int, string>
     */
    public static function activeOptionsForStore(int $storeId): array
    {
        return static::forStore($storeId)
            ->active()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Default cash account for new entries: prefer an active "cash" account,
     * otherwise the first active account of any type.
     */
    public static function defaultActiveAccountIdForStore(int $storeId): ?int
    {
        $cashId = static::forStore($storeId)
            ->active()
            ->where('type', 'cash')
            ->orderBy('name')
            ->value('id');

        if ($cashId !== null) {
            return (int) $cashId;
        }

        $fallbackId = array_key_first(static::activeOptionsForStore($storeId));

        return $fallbackId === null ? null : (int) $fallbackId;
    }

    /**
     * Current balance per cash account for a store, keyed by cash account id.
     *
     * Balance = payments + optical order payments + manual cash-ins - expenses.
     * Records with a null cash_account_id belong to no account and are excluded.
     *
     * @return array<int, float>
     */
    public static function balancesForStore(int $storeId): array
    {
        /** @var array<class-string<Model>, int> $sources model => direction of the money flow */
        $sources = [
            Payment::class => 1,
            OpticalOrderPayment::class => 1,
            CashIn::class => 1,
            Expense::class => -1,
        ];

        $balances = [];

        foreach ($sources as $model => $direction) {
            $totals = $model::query()
                ->forStore($storeId)
                ->whereNotNull('cash_account_id')
                ->groupBy('cash_account_id')
                ->selectRaw('cash_account_id, SUM(amount) as total')
                ->toBase()
                ->get();

            foreach ($totals as $row) {
                $accountId = (int) $row->cash_account_id;
                $balances[$accountId] = ($balances[$accountId] ?? 0.0) + ($direction * (float) $row->total);
            }
        }

        return $balances;
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
