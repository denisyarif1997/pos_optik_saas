<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first() ?? Store::create([
            'name' => 'Toko Optik Demo', 'slug' => 'toko-optik-demo',
            'phone' => '081234567890', 'is_active' => true,
        ]);

        $categories = [
            ['name' => 'Frame', 'slug' => 'frame', 'store_id' => $store->id],
            ['name' => 'Lensa', 'slug' => 'lensa', 'store_id' => $store->id],
            ['name' => 'Lensa RX', 'slug' => 'lensa-rx', 'store_id' => $store->id],
            ['name' => 'Lensa Stock', 'slug' => 'lensa-stock', 'store_id' => $store->id],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'store_id' => $store->id],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
