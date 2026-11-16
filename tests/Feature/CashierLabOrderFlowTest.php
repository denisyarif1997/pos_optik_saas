<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\LabOrder;
use App\Models\OpticalOrder;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\StockLensMatrix;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierLabOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeStoreWithOwner(string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create([
            'name' => 'Toko Optik Test',
            'slug' => 'toko-optik-test-'.str()->random(6),
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }

    public function test_cashier_creates_optical_order_and_lab_order(): void
    {
        [$user, $store] = $this->makeStoreWithOwner('kasir@test.test');

        $product = Product::create([
            'store_id' => $store->id,
            'type' => 'FRAME',
            'sku' => 'FRM-TEST-001',
            'name' => 'Frame Test',
            'brand' => 'TestBrand',
            'cost_price' => 400000,
            'sell_price' => 750000,
            'stock' => 12,
            'min_stock' => 3,
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'store_id' => $store->id,
            'name' => 'Budi Santoso',
            'phone' => '081234567801',
            'is_active' => true,
        ]);

        $prescription = Prescription::create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'checked_at' => now()->subMonths(2),
            'od_sph' => -1.50,
            'od_cyl' => -0.50,
            'od_axis' => 180,
            'od_add' => 0.00,
            'os_sph' => -1.75,
            'os_cyl' => -0.50,
            'os_axis' => 170,
            'os_add' => 0.00,
            'pd' => 62.0,
            'notes' => 'Resep test',
        ]);

        $this->actingAs($user);

        $order = OpticalOrder::create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'prescription_id' => $prescription->id,
            'order_number' => 'SR-1-00001',
            'code' => 'OO-TEST-0001',
            'lab_name' => 'Lab Jaya Optik',
            'prescription_snapshot' => $prescription->only([
                'od_sph', 'od_cyl', 'od_axis', 'od_add',
                'os_sph', 'os_cyl', 'os_axis', 'os_add', 'pd',
            ]),
            'ordered_at' => now(),
            'estimated_done_at' => now()->addDays(7),
            'promised_date' => now()->addDays(10),
            'total' => 2250000,
            'dp_total' => 750000,
            'status' => 'DRAFT',
            'work_notes' => 'Test optical order',
        ]);

        $labOrder = LabOrder::create([
            'store_id' => $store->id,
            'order_id' => $order->id,
            'lab_supplier_name' => 'Lab Jaya Optik',
            'status' => 'MENUNGGU',
            'lab_cost' => 0,
            'expected_completion_date' => now()->addDays(10),
        ]);

        $this->assertDatabaseHas('optical_orders', [
            'id' => $order->id,
            'status' => 'DRAFT',
            'lab_name' => 'Lab Jaya Optik',
        ]);

        $this->assertDatabaseHas('lab_orders', [
            'id' => $labOrder->id,
            'order_id' => $order->id,
            'status' => 'MENUNGGU',
            'lab_supplier_name' => 'Lab Jaya Optik',
        ]);

        $this->assertEquals('MENUNGGU', $labOrder->fresh()->status);
        $this->assertEquals('DRAFT', $order->fresh()->status);

        $labOrder->update(['status' => 'DIPESAN']);
        $order->update(['status' => 'DIPROSES']);

        $this->assertEquals('DIPESAN', $labOrder->fresh()->status);
        $this->assertEquals('DIPROSES', $order->fresh()->status);

        $labOrder->update(['status' => 'DITERIMA']);
        $order->update(['status' => 'SIAP', 'taken_at' => now()->toDateString()]);

        $this->assertEquals('DITERIMA', $labOrder->fresh()->status);
        $this->assertEquals('SIAP', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->taken_at);

        $labOrder->update([
            'status' => 'SALAH',
            'rejection_reason' => 'Lensa tidak sesuai resep',
        ]);
        $order->update(['status' => 'PESANAN']);

        $this->assertEquals('SALAH', $labOrder->fresh()->status);
        $this->assertEquals('Lensa tidak sesuai resep', $labOrder->fresh()->rejection_reason);
        $this->assertEquals('PESANAN', $order->fresh()->status);
    }

    public function test_stock_lens_matrix_low_stock_and_out_of_stock_scopes(): void
    {
        [$user, $store] = $this->makeStoreWithOwner('stok@test.test');

        $product = Product::create([
            'store_id' => $store->id,
            'type' => 'STOCK_LENS',
            'sku' => 'LENS-TEST-001',
            'name' => 'Lensa Stock Test',
            'cost_price' => 150000,
            'sell_price' => 300000,
            'stock' => 5,
            'min_stock' => 2,
            'is_active' => true,
        ]);

        StockLensMatrix::create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'sph' => -2.00,
            'cyl' => -0.50,
            'add_val' => 0.00,
            'stock_qty' => 0,
            'min_stock_alert' => 2,
        ]);

        StockLensMatrix::create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'sph' => -1.50,
            'cyl' => -0.50,
            'add_val' => 0.00,
            'stock_qty' => 1,
            'min_stock_alert' => 2,
        ]);

        StockLensMatrix::create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'sph' => 0.00,
            'cyl' => 0.00,
            'add_val' => 0.00,
            'stock_qty' => 8,
            'min_stock_alert' => 2,
        ]);

        $outOfStock = StockLensMatrix::forStore($store->id)
            ->forProduct($product->id)
            ->outOfStock()
            ->count();

        $this->assertEquals(1, $outOfStock, 'Should have exactly 1 out-of-stock matrix entry');

        $lowStock = StockLensMatrix::forStore($store->id)
            ->forProduct($product->id)
            ->lowStock()
            ->count();

        $this->assertEquals(2, $lowStock, 'Should have exactly 2 low-stock matrix entries (out-of-stock + low-stock)');
    }

    public function test_optik_pos_seeder_is_idempotent(): void
    {
        $this->artisan('db:seed', ['--class' => "Database\Seeders\OptikPosSeeder", '--force' => true]);

        $store = Store::where('slug', 'toko-optik-demo')->first();
        $this->assertNotNull($store);

        $initialProductCount = Product::forStore($store->id)->count();
        $initialMatrixCount = StockLensMatrix::forStore($store->id)->count();
        $initialOrderCount = OpticalOrder::forStore($store->id)->count();
        $initialLabOrderCount = LabOrder::forStore($store->id)->count();
        $initialCustomerCount = Customer::forStore($store->id)->count();

        $this->artisan('db:seed', ['--class' => "Database\Seeders\OptikPosSeeder", '--force' => true]);

        $this->assertEquals($initialProductCount, Product::forStore($store->id)->count());
        $this->assertEquals($initialMatrixCount, StockLensMatrix::forStore($store->id)->count());
        $this->assertEquals($initialOrderCount, OpticalOrder::forStore($store->id)->count());
        $this->assertEquals($initialLabOrderCount, LabOrder::forStore($store->id)->count());
        $this->assertEquals($initialCustomerCount, Customer::forStore($store->id)->count());

        $this->assertEquals(1, Store::where('slug', 'toko-optik-demo')->count());
    }

    public function test_cross_store_lab_order_isolation(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('a@test.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('b@test.test');

        $customerA = Customer::create([
            'store_id' => $storeA->id,
            'name' => 'Customer A',
            'phone' => '08111111111',
            'is_active' => true,
        ]);
        $customerB = Customer::create([
            'store_id' => $storeB->id,
            'name' => 'Customer B',
            'phone' => '08222222222',
            'is_active' => true,
        ]);

        $orderA = OpticalOrder::create([
            'store_id' => $storeA->id,
            'customer_id' => $customerA->id,
            'order_number' => 'SR-A-00001',
            'code' => 'OO-A-0001',
            'lab_name' => 'Lab A',
            'status' => 'DRAFT',
        ]);
        $orderB = OpticalOrder::create([
            'store_id' => $storeB->id,
            'customer_id' => $customerB->id,
            'order_number' => 'SR-B-00001',
            'code' => 'OO-B-0001',
            'lab_name' => 'Lab B',
            'status' => 'DRAFT',
        ]);

        LabOrder::create([
            'store_id' => $storeA->id,
            'order_id' => $orderA->id,
            'lab_supplier_name' => 'Lab A',
            'status' => 'MENUNGGU',
        ]);
        LabOrder::create([
            'store_id' => $storeB->id,
            'order_id' => $orderB->id,
            'lab_supplier_name' => 'Lab B',
            'status' => 'MENUNGGU',
        ]);

        $this->actingAs($userA);

        $labOrdersA = LabOrder::forStore($storeA->id)->get();
        $this->assertEquals(1, $labOrdersA->count());
        $this->assertEquals('Lab A', $labOrdersA->first()->lab_supplier_name);

        $foundInA = LabOrder::forStore($storeA->id)->where('order_id', $orderB->id)->first();
        $this->assertNull($foundInA);
    }
}
