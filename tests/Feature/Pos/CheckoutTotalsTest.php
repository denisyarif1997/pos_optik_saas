<?php

namespace Tests\Feature\Pos;

use App\Livewire\Pos\Cashier;
use App\Livewire\Sales\Show as SaleShow;
use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_applies_transaction_discount_before_ppn_and_records_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Kacamata', 100000);
        $cashAccount = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 2,
                'discount' => 0,
                'item_type' => 'sale',
            ]])
            ->set('discount_amount', 20000)
            ->set('apply_ppn', true)
            ->assertDispatched('cashier-total-updated', total: 199800)
            ->set('cash_account_id', $cashAccount->id)
            ->set('pay_amount', 199800)
            ->call('checkout')
            ->assertHasNoErrors();

        $sale = Sale::forStore($store->id)->firstOrFail();

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'subtotal' => 200000,
            'discount_total' => 20000,
            'discount_percent' => 0,
            'discount_amount' => 20000,
            'ppn_rate' => 11,
            'ppn_amount' => 19800,
            'total' => 199800,
            'paid_total' => 199800,
        ]);
        $this->assertDatabaseHas('payments', [
            'sale_id' => $sale->id,
            'cash_account_id' => $cashAccount->id,
            'method' => 'cash',
            'amount' => 199800,
        ]);

        $this->get(route('print.sale', ['id' => $sale->id]))
            ->assertOk()
            ->assertSee('Diskon')
            ->assertSee('PPN')
            ->assertSee('Kas Bank');
    }

    public function test_cashier_rejects_payment_to_another_stores_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        [, $otherStore] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Frame', 100000);
        $otherAccount = CashAccount::forStore($otherStore->id)->firstOrFail();

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 1,
                'discount' => 0,
                'item_type' => 'sale',
            ]])
            ->set('pay_amount', 100000)
            ->set('cash_account_id', $otherAccount->id)
            ->call('checkout')
            ->assertHasErrors(['cash_account_id']);

        $this->assertDatabaseCount('sales', 0);
    }

    public function test_cashier_rejects_transaction_discount_above_the_subtotal(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Diskon', 100000);

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 1,
                'discount' => 0,
                'item_type' => 'sale',
            ]])
            ->set('discount_amount', 150000)
            ->call('checkout')
            ->assertHasErrors(['discount_amount']);

        $this->assertDatabaseCount('sales', 0);
    }

    public function test_later_sale_payment_records_the_selected_cash_account(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Pelunasan', 100000);
        $sale = app(SaleService::class)->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 1]],
        ]);
        $cashAccount = CashAccount::forStore($store->id)->where('code', 'kas-bank')->firstOrFail();

        Livewire::test(SaleShow::class, ['id' => $sale->id])
            ->set('amount', 25000)
            ->set('method', 'transfer')
            ->set('cashAccountId', $cashAccount->id)
            ->call('addPayment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('payments', [
            'sale_id' => $sale->id,
            'cash_account_id' => $cashAccount->id,
            'method' => 'transfer',
            'amount' => 25000,
        ]);
    }

    public function test_stock_staff_cannot_record_a_sale_payment(): void
    {
        [$owner, $store] = $this->makeStoreWithOwner();
        $this->actingAs($owner);
        $product = $this->makeProduct($store, 'Produk Terbuka', 100000);
        $sale = app(SaleService::class)->create($owner, [
            'items' => [['product_id' => $product->id, 'qty' => 1]],
        ]);
        $cashAccount = CashAccount::forStore($store->id)->firstOrFail();
        $staff = User::factory()->create();
        $store->members()->attach($staff->id, ['role' => 'stok', 'is_active' => true]);
        $this->actingAs($staff);

        Livewire::test(SaleShow::class, ['id' => $sale->id])
            ->set('amount', 25000)
            ->set('cashAccountId', $cashAccount->id)
            ->call('addPayment')
            ->assertForbidden();

        $this->assertDatabaseCount('payments', 0);
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Checkout',
            'slug' => 'toko-checkout-'.str()->random(8),
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }

    private function makeProduct(Store $store, string $name, int $price): Product
    {
        return Product::create([
            'store_id' => $store->id,
            'name' => $name,
            'sell_price' => $price,
            'cost_price' => $price / 2,
            'stock' => 10,
            'is_active' => true,
        ]);
    }
}
