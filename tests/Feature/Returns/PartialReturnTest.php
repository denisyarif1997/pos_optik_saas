<?php

namespace Tests\Feature\Returns;

use App\Livewire\Returns\Index as ReturnIndex;
use App\Models\CashAccount;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PartialReturnTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_return_one_item_from_multi_item_sale(): void
    {
        $user = User::factory()->create(['email' => 'partial-return@example.test']);
        $store = Store::create([
            'name' => 'Toko Retur Parsial',
            'slug' => 'toko-retur-parsial',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);

        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $productA = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame A',
            'sell_price' => 100000,
            'stock' => 10,
        ]);
        $productB = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame B',
            'sell_price' => 100000,
            'stock' => 10,
        ]);

        $sale = app(SaleService::class)->create($user, [
            'items' => [
                ['product_id' => $productA->id, 'qty' => 2],
                ['product_id' => $productB->id, 'qty' => 1],
            ],
            'payments' => [['method' => 'cash', 'amount' => 500000, 'cash_account_id' => $cashAccountId]],
        ]);

        Livewire::test(ReturnIndex::class)
            ->set('code', $sale->code)
            ->call('findSale')
            ->assertSet('sale_id', $sale->id)
            ->set('product_id', $productA->id)
            ->set('qty', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('returns', [
            'sale_id' => $sale->id,
            'product_id' => $productA->id,
            'qty' => 1,
        ]);
    }

    public function test_cannot_return_more_than_remaining_qty(): void
    {
        $user = User::factory()->create(['email' => 'partial-return-limit@example.test']);
        $store = Store::create([
            'name' => 'Toko Batas Retur',
            'slug' => 'toko-batas-retur',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);

        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $productA = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Limit',
            'sell_price' => 100000,
            'stock' => 10,
        ]);

        $sale = app(SaleService::class)->create($user, [
            'items' => [
                ['product_id' => $productA->id, 'qty' => 2],
            ],
            'payments' => [['method' => 'cash', 'amount' => 500000, 'cash_account_id' => $cashAccountId]],
        ]);

        Livewire::test(ReturnIndex::class)
            ->set('code', $sale->code)
            ->call('findSale')
            ->set('product_id', $productA->id)
            ->set('qty', 3) // melebihi qty pembelian (2)
            ->call('save')
            ->assertHasErrors('qty');
    }
}
