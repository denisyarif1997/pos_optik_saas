<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function makeStoreWithOwner(string $storeName, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create(['name' => $storeName, 'slug' => str()->slug($storeName.'-'.uniqid())]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }

    public function test_user_cannot_see_other_store_product_via_query(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'a@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'b@toko.test');

        $productB = Product::create([
            'store_id' => $storeB->id,
            'name' => 'Frame B',
            'sell_price' => 100000,
        ]);

        $this->actingAs($userA);

        $found = Product::forActiveStore()->where('id', $productB->id)->first();

        $this->assertNull($found, 'Produk Toko B bocor ke query Toko A!');
    }

    public function test_single_user_single_store_and_isolation(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'a2@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'b2@toko.test');

        $this->assertEquals($storeA->id, $userA->storeId());
        $this->assertEquals($storeB->id, $userB->storeId());
        $this->assertNotEquals($userA->storeId(), $userB->storeId());

        $this->assertTrue($userA->canOwnAnotherStore() === false);

        $this->expectException(QueryException::class);
        $storeB->members()->attach($userA->id, ['role' => 'kasir', 'is_active' => true]);
    }

    public function test_policy_blocks_cross_store_product_access(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'a3@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'b3@toko.test');

        $productB = Product::create(['store_id' => $storeB->id, 'name' => 'Lensa B', 'sell_price' => 50000]);

        $this->actingAs($userA);

        $this->assertFalse($userA->can('view', $productB), 'Policy membiarkan akses lintas toko!');
    }

    public function test_dashboard_only_counts_active_store(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'a4@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'b4@toko.test');

        Product::create(['store_id' => $storeA->id, 'name' => 'Frame A', 'sell_price' => 100000, 'stock' => 1, 'min_stock' => 5]);
        Product::create(['store_id' => $storeB->id, 'name' => 'Frame B', 'sell_price' => 100000, 'stock' => 1, 'min_stock' => 5]);
        Product::create(['store_id' => $storeB->id, 'name' => 'Frame B2', 'sell_price' => 100000, 'stock' => 1, 'min_stock' => 5]);

        $countA = Product::forStore($storeA->id)->whereColumn('stock', '<=', 'min_stock')->count();

        $this->assertEquals(1, $countA, 'Dashboard tercampur data toko lain!');
    }
}
