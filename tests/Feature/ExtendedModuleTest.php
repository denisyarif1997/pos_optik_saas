<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnModel;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warranty;
use App\Services\PurchaseService;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtendedModuleTest extends TestCase
{
    use RefreshDatabase;

    private function makeStoreWithOwner(string $storeName, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create(['name' => $storeName, 'slug' => str()->slug($storeName.'-'.uniqid())]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }

    public function test_purchase_adds_stock_and_is_scoped(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'ea@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'eb@toko.test');

        $product = Product::create(['store_id' => $storeA->id, 'name' => 'Frame A', 'sell_price' => 100000, 'stock' => 2]);
        $supplier = Supplier::create(['store_id' => $storeA->id, 'name' => 'Sup A']);

        $this->actingAs($userA);
        $purchase = app(PurchaseService::class)->create($userA, [
            'supplier_id' => $supplier->id,
            'items' => [['product_id' => $product->id, 'qty' => 3, 'buy_price' => 50000]],
        ]);

        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertEquals($storeA->id, $purchase->store_id);
        $this->assertNull(Purchase::forActiveStore()->where('id', $purchase->id)->first() === null ? null : $this->switchToB($userB, $purchase));
    }

    private function switchToB($userB, $purchase)
    {
        $this->actingAs($userB);

        return Purchase::forActiveStore()->where('id', $purchase->id)->first();
    }

    public function test_return_and_expense_warranty_scoped(): void
    {
        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'fa@toko.test');
        [$userB, $storeB] = $this->makeStoreWithOwner('Toko B', 'fb@toko.test');

        $this->actingAs($userA);

        $product = Product::create(['store_id' => $storeA->id, 'name' => 'Frame A', 'sell_price' => 100000, 'stock' => 10]);
        $sale = app(SaleService::class)->create($userA, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000]],
        ]);

        $retur = app(ReturnService::class)->create($userA, $sale->id, $product->id, 1, 'cacat');
        $this->assertEquals(9, $product->fresh()->stock);

        $customer = Customer::create(['store_id' => $storeA->id, 'name' => 'Ani']);
        $warranty = Warranty::create(['store_id' => $storeA->id, 'customer_id' => $customer->id, 'sale_id' => $sale->id, 'code' => 'GRS-1']);
        $expense = Expense::create(['store_id' => $storeA->id, 'spent_at' => now()->toDateString(), 'description' => 'Listrik', 'amount' => 50000]);

        $this->actingAs($userB);
        $this->assertNull(ReturnModel::forActiveStore()->where('id', $retur->id)->first());
        $this->assertNull(Warranty::forActiveStore()->where('id', $warranty->id)->first());
        $this->assertNull(Expense::forActiveStore()->where('id', $expense->id)->first());
    }
}
