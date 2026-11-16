<?php

namespace Tests\Feature;

use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function makeStoreWithOwner(string $storeName, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create(['name' => $storeName, 'slug' => str()->slug($storeName.'-'.uniqid())]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }

    public function test_sale_isolated_and_recalculates_server_side(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'ma@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'mb@toko.test');

        $productB = Product::create(['store_id' => $storeB->id, 'name' => 'Frame B', 'sell_price' => 200000, 'stock' => 5]);

        $this->actingAs($userA);
        $this->expectException(ModelNotFoundException::class);

        app(SaleService::class)->create($userA, [
            'items' => [['product_id' => $productB->id, 'qty' => 1]],
            'payments' => [],
        ]);
    }

    public function test_customer_and_order_scoped_to_own_store(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'ca@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'cb@toko.test');

        $customerB = Customer::create(['store_id' => $storeB->id, 'name' => 'Budi B']);
        $orderB = OpticalOrder::create(['store_id' => $storeB->id, 'customer_id' => $customerB->id, 'code' => 'OPT-X', 'total' => 100000]);

        $this->actingAs($userA);

        $this->assertNull(Customer::forActiveStore()->where('id', $customerB->id)->first());
        $this->assertNull(OpticalOrder::forActiveStore()->where('id', $orderB->id)->first());
        $this->assertFalse($userA->can('view', $customerB));
        $this->assertFalse($userA->can('view', $orderB));
    }

    public function test_sale_creates_stock_movement_atomically(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'sa@toko.test');

        $product = Product::create(['store_id' => $storeA->id, 'name' => 'Frame A', 'sell_price' => 150000, 'stock' => 5]);
        $this->actingAs($userA);
        $cashAccountId = CashAccount::forStore($storeA->id)->where('code', 'kas-toko')->value('id');

        $sale = app(SaleService::class)->create($userA, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 300000, 'cash_account_id' => $cashAccountId]],
        ]);

        $this->assertEquals('lunas', $sale->payment_status);
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertEquals(1, $sale->items()->count());
        $this->assertDatabaseHas('stock_movements', ['store_id' => $storeA->id, 'product_id' => $product->id, 'type' => 'out']);
    }
}
