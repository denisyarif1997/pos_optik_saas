<?php

namespace Database\Seeders;

use App\Models\CashAccount;
use App\Models\Store;
use Illuminate\Database\Seeder;

class CashAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::query()->each(static function (Store $store): void {
            CashAccount::ensureDefaultsForStore($store);
        });
    }
}
