<?php

namespace Tests\Feature\Returns;

use App\Models\CashAccount;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_return_history_displays_sale_transaction_number(): void
    {
        $user = User::factory()->create(['email' => 'return-history@example.test']);
        $store = Store::create([
            'name' => 'Toko Riwayat Retur',
            'slug' => 'toko-riwayat-retur',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);
        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Riwayat',
            'sell_price' => 100000,
            'stock' => 5,
        ]);
        $sale = app(SaleService::class)->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
        ]);
        app(ReturnService::class)->create($user, $sale->id, $product->id, 1);

        $this->get(route('returns.index'))
            ->assertOk()
            ->assertSee('No. Transaksi:')
            ->assertSee($sale->code);
    }
}
