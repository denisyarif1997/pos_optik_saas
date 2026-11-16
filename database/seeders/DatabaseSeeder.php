<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('Seeder dilewati di environment production.');

            return;
        }

        $this->call([
            PlanSeeder::class,
            OptikPosSeeder::class,
            CashAccountSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
