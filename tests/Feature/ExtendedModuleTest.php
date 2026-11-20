<?php

namespace Tests\Feature;

use App\Livewire\OpticalOrders\Form as OpticalOrderForm;
use App\Livewire\Sales\History;
use App\Livewire\Warranties\Index as WarrantyIndex;
use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\OpticalOrder;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warranty;
use App\Services\PurchaseService;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
        $cashAccountId = CashAccount::forStore($storeA->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create(['store_id' => $storeA->id, 'name' => 'Frame A', 'sell_price' => 100000, 'stock' => 10]);
        $sale = app(SaleService::class)->create($userA, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
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

    public function test_optical_order_form_generates_a_code_when_saving(): void
    {
        $this->travelTo('2026-06-15 10:00:00');

        [$user, $store] = $this->makeStoreWithOwner('Toko Optik', 'order-form@toko.test');
        $customer = Customer::create(['store_id' => $store->id, 'name' => 'Pelanggan']);
        $this->actingAs($user);

        Livewire::test(OpticalOrderForm::class)
            ->set('customer_id', $customer->id)
            ->call('save')
            ->assertHasNoErrors();

        $order = OpticalOrder::forStore($store->id)->firstOrFail();
        $this->assertSame('OPT-20260615-0001', $order->code);
    }

    public function test_warranty_index_saves_claim_notes(): void
    {
        [$user, $store] = $this->makeStoreWithOwner('Toko Garansi', 'warranty-index@toko.test');
        $customer = Customer::create(['store_id' => $store->id, 'name' => 'Pelanggan']);
        $this->actingAs($user);

        Livewire::test(WarrantyIndex::class)
            ->set('customer_id', $customer->id)
            ->set('claim_notes', 'Engsel perlu diperiksa')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('warranties', [
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'claim_notes' => 'Engsel perlu diperiksa',
        ]);
    }

    public function test_sales_history_defaults_to_last_30_days_filter(): void
    {
        $this->travelTo('2026-06-15 10:00:00');

        [$userA, $storeA] = $this->makeStoreWithOwner('Toko A', 'sa-filter@toko.test');
        $this->actingAs($userA);

        Sale::create([
            'store_id' => $storeA->id,
            'cashier_id' => $userA->id,
            'code' => 'INV-RECENT',
            'sold_at' => '2026-06-01 12:00:00',
            'subtotal' => 100000,
            'discount_total' => 0,
            'total' => 100000,
            'paid_total' => 100000,
            'payment_status' => 'lunas',
            'status' => 'selesai',
        ]);

        Sale::create([
            'store_id' => $storeA->id,
            'cashier_id' => $userA->id,
            'code' => 'INV-OLD',
            'sold_at' => '2026-04-10 12:00:00',
            'subtotal' => 100000,
            'discount_total' => 0,
            'total' => 100000,
            'paid_total' => 100000,
            'payment_status' => 'lunas',
            'status' => 'selesai',
        ]);

        Livewire::test(History::class)
            ->assertSet('from', '2026-05-17')
            ->assertSet('to', '2026-06-15')
            ->assertSee('INV-RECENT')
            ->assertDontSee('INV-OLD')
            ->set('from', '2026-04-01')
            ->assertSee('INV-OLD');
    }
}
