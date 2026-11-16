<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first() ?? Store::create([
            'name' => 'Toko Optik Demo', 'slug' => 'toko-optik-demo',
            'phone' => '081234567890', 'is_active' => true,
        ]);

        $suppliers = [
            ['name' => 'Distributor Optik Jaya', 'phone' => '081234567810', 'address' => 'Jl. Mata No. 1', 'store_id' => $store->id, 'is_active' => true],
            ['name' => 'Farma Kacamata', 'phone' => '081234567811', 'address' => 'Jl. Kaca No. 10', 'store_id' => $store->id, 'is_active' => true],
            ['name' => 'Lensa Internasional', 'phone' => '081234567812', 'address' => 'Jl. Internasional No. 5', 'store_id' => $store->id, 'is_active' => true],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(['name' => $supplier['name']], $supplier);
        }
    }
}
