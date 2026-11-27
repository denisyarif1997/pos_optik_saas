<?php

namespace Tests\Feature\Pos;

use App\Livewire\Pos\Cashier;
use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItemDiscountPpnTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_renders_per_item_discount_and_ppn_controls(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Kacamata Diskon', 100000);

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 1,
                'discount' => 25000,
                'ppn' => true,
                'item_type' => 'sale',
            ]])
            ->assertSee('Diskon item (Rp)')
            ->assertSee('Diskon transaksi (Rp)')
            ->assertSee('PPN 11% semua item')
            ->assertSee('= Rp 75.000')
            ->assertDispatched('cashier-total-updated', total: 83250);
    }

    public function test_per_item_discount_and_per_item_ppn_are_stored_on_the_sale(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $taxed = $this->makeProduct($store, 'Frame Kena PPN', 100000);
        $untaxed = $this->makeProduct($store, 'Lensa Tanpa PPN', 50000);

        Livewire::test(Cashier::class)
            ->set('cart', [
                ['product_id' => $taxed->id, 'qty' => 1, 'discount' => 10000, 'ppn' => true, 'item_type' => 'sale'],
                ['product_id' => $untaxed->id, 'qty' => 1, 'discount' => 0, 'ppn' => false, 'item_type' => 'sale'],
            ])
            ->assertDispatched('cashier-total-updated', total: 149900)
            ->call('checkout')
            ->assertHasNoErrors();

        $sale = Sale::forStore($store->id)->firstOrFail();

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'subtotal' => 150000,
            'discount_total' => 10000,
            'ppn_rate' => 11,
            'ppn_amount' => 9900,
            'total' => 149900,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $taxed->id,
            'discount' => 10000,
            'ppn_rate' => 11,
            'ppn_amount' => 9900,
            'subtotal' => 90000,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $untaxed->id,
            'discount' => 0,
            'ppn_rate' => 0,
            'ppn_amount' => 0,
            'subtotal' => 50000,
        ]);
    }

    public function test_transaction_discount_is_allocated_across_lines_before_per_item_ppn(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $taxed = $this->makeProduct($store, 'Produk Kena PPN', 100000);
        $untaxed = $this->makeProduct($store, 'Produk Bebas PPN', 100000);

        Livewire::test(Cashier::class)
            ->set('cart', [
                ['product_id' => $taxed->id, 'qty' => 1, 'discount' => 0, 'ppn' => true, 'item_type' => 'sale'],
                ['product_id' => $untaxed->id, 'qty' => 1, 'discount' => 0, 'ppn' => false, 'item_type' => 'sale'],
            ])
            ->set('discount_amount', 20000)
            ->assertDispatched('cashier-total-updated', total: 189900)
            ->call('checkout')
            ->assertHasNoErrors();

        $sale = Sale::forStore($store->id)->firstOrFail();

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'subtotal' => 200000,
            'discount_total' => 20000,
            'discount_amount' => 20000,
            'ppn_amount' => 9900,
            'total' => 189900,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $taxed->id,
            'ppn_amount' => 9900,
            'subtotal' => 100000,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $untaxed->id,
            'ppn_amount' => 0,
            'subtotal' => 100000,
        ]);
    }

    public function test_per_item_discount_is_clamped_to_the_line_total_while_typing(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Clamp', 100000);

        $component = Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 2,
                'discount' => 0,
                'ppn' => false,
                'item_type' => 'sale',
            ]])
            ->set('cart.0.discount', 250000)
            ->assertDispatched('cashier-total-updated', total: 0);

        $this->assertSame(200000.0, (float) $component->get('cart.0.discount'));
    }

    public function test_checkout_rejects_an_item_discount_above_the_line_total(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Validasi', 100000);

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 1,
                'discount' => 150000,
                'ppn' => false,
                'item_type' => 'sale',
            ]])
            ->call('checkout')
            ->assertHasErrors(['cart.0.discount']);

        $this->assertDatabaseCount('sales', 0);
    }

    public function test_global_ppn_toggle_marks_every_item_and_unchecks_with_the_items(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $first = $this->makeProduct($store, 'Produk Satu', 100000);
        $second = $this->makeProduct($store, 'Produk Dua', 50000);

        Livewire::test(Cashier::class)
            ->set('cart', [
                ['product_id' => $first->id, 'qty' => 1, 'discount' => 0, 'ppn' => false, 'item_type' => 'sale'],
                ['product_id' => $second->id, 'qty' => 1, 'discount' => 0, 'ppn' => false, 'item_type' => 'sale'],
            ])
            ->set('apply_ppn', true)
            ->assertSet('cart.0.ppn', true)
            ->assertSet('cart.1.ppn', true)
            ->assertDispatched('cashier-total-updated', total: 166500)
            ->set('cart.1.ppn', false)
            ->assertSet('apply_ppn', false)
            ->assertSet('cart.0.ppn', true)
            ->assertDispatched('cashier-total-updated', total: 161000);
    }

    public function test_new_cart_rows_follow_the_active_ppn_toggle(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Baru', 100000);

        $component = Livewire::test(Cashier::class)
            ->set('apply_ppn', true)
            ->call('addToCart', $product->id);

        $this->assertSame(true, $component->get('cart.0.ppn'));
    }

    public function test_sale_detail_and_receipt_show_per_item_discount_and_ppn(): void
    {
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $product = $this->makeProduct($store, 'Produk Nota', 100000);

        Livewire::test(Cashier::class)
            ->set('cart', [[
                'product_id' => $product->id,
                'qty' => 1,
                'discount' => 10000,
                'ppn' => true,
                'item_type' => 'sale',
            ]])
            ->call('checkout')
            ->assertHasNoErrors();

        $sale = Sale::forStore($store->id)->firstOrFail();

        $this->get(route('sales.show', ['id' => $sale->id]))
            ->assertOk()
            ->assertSee('diskon -Rp 10.000')
            ->assertSee('PPN +Rp 9.900');

        $this->get(route('print.sale', ['id' => $sale->id]))
            ->assertOk()
            ->assertSee('diskon -10.000')
            ->assertSee('PPN +9.900');
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Item Diskon',
            'slug' => 'toko-item-diskon-'.str()->random(8),
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
