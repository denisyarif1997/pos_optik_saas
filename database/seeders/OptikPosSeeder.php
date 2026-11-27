<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\StockLensMatrix;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptikPosSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first() ?? Store::create([
            'name' => 'Toko Optik Demo', 'slug' => 'toko-optik-demo',
            'phone' => '081234567890', 'is_active' => true,
        ]);

        $this->truncatePosTables($store->id);

        $products = [
            Product::create(['store_id' => $store->id, 'type' => 'FRAME', 'sku' => 'FRM-001', 'name' => 'Frame Titanium Kantong', 'brand' => 'DemoOptic', 'cost_price' => 400000, 'sell_price' => 750000, 'stock' => 12, 'min_stock' => 3, 'is_active' => true]),
            Product::create(['store_id' => $store->id, 'type' => 'STOCK_LENS', 'sku' => 'LENS-STOCK-001', 'name' => 'Lensa Stock Spheric', 'cost_price' => 150000, 'sell_price' => 300000, 'stock' => 5, 'min_stock' => 2, 'is_active' => true]),
            Product::create(['store_id' => $store->id, 'type' => 'RX_LENS', 'sku' => 'LENS-RX-001', 'name' => 'Lensa RX Progressive', 'cost_price' => 600000, 'sell_price' => 1200000, 'stock' => 3, 'min_stock' => 1, 'is_active' => true]),
            Product::create(['store_id' => $store->id, 'type' => 'CONTACT_LENS', 'sku' => 'CL-001', 'name' => 'Softlens Harian', 'cost_price' => 80000, 'sell_price' => 150000, 'stock' => 20, 'min_stock' => 5, 'is_active' => true]),
            Product::create(['store_id' => $store->id, 'type' => 'SERVICE', 'sku' => 'SVC-001', 'name' => 'Pasang & Faset Lensa', 'cost_price' => 0, 'sell_price' => 75000, 'stock' => 999, 'min_stock' => 0, 'is_active' => true]),
        ];

        $stockLensProduct = $products[1];
        foreach ([-2.00, -1.50, -1.00, -0.50, 0.00, 0.50, 1.00] as $sph) {
            foreach ([0.00, -0.50, -1.00, -1.50, -2.00] as $cyl) {
                StockLensMatrix::create(['store_id' => $store->id, 'product_id' => $stockLensProduct->id, 'sph' => $sph, 'cyl' => $cyl, 'add_val' => 0.00, 'stock_qty' => fake()->numberBetween(0, 8), 'min_stock_alert' => 2]);
            }
        }

        $customers = [
            Customer::create(['store_id' => $store->id, 'name' => 'Budi Santoso', 'phone' => '081234567801', 'is_active' => true]),
            Customer::create(['store_id' => $store->id, 'name' => 'Siti Rahayu', 'phone' => '081234567802', 'is_active' => true]),
        ];

        $prescriptions = [
            Prescription::create(['store_id' => $store->id, 'customer_id' => $customers[0]->id, 'checked_at' => now()->subMonths(2), 'od_sph' => -1.50, 'od_cyl' => -0.50, 'od_axis' => 180, 'od_add' => 0.00, 'os_sph' => -1.75, 'os_cyl' => -0.50, 'os_axis' => 170, 'os_add' => 0.00, 'pd' => 62.0, 'notes' => 'Resep pertama kali']),
            Prescription::create(['store_id' => $store->id, 'customer_id' => $customers[1]->id, 'checked_at' => now()->subMonths(1), 'od_sph' => -2.00, 'od_cyl' => -1.00, 'od_axis' => 10, 'od_add' => 0.00, 'os_sph' => -2.25, 'os_cyl' => -1.00, 'os_axis' => 175, 'os_add' => 0.00, 'pd' => 60.5, 'notes' => 'Controll lensa minus']),
        ];

        $snapshot = fn ($p) => $p->only(['od_sph', 'od_cyl', 'od_axis', 'od_add', 'os_sph', 'os_cyl', 'os_axis', 'os_add', 'pd']);

    }

    protected function truncatePosTables(int $storeId): void
    {
        $tables = ['sale_items', 'sales', 'prescriptions', 'customers', 'stock_lens_matrices', 'products'];
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            DB::table($table)->where('store_id', $storeId)->delete();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
