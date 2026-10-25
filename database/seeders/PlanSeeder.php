<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Basic', 'slug' => 'basic', 'price' => 99000, 'duration_days' => 30, 'features' => ['kasir', 'produk', 'laporan-dasar'], 'is_active' => true],
            ['name' => 'Pro', 'slug' => 'pro', 'price' => 199000, 'duration_days' => 30, 'features' => ['kasir', 'produk', 'pesanan-kacamata', 'laporan-lengkap'], 'is_active' => true],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
