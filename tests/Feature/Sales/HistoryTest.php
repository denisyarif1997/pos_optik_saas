<?php

namespace Tests\Feature\Sales;

use App\Livewire\Sales\History;
use App\Models\CashAccount;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_history_shows_net_values_and_return_status(): void
    {
        $this->travelTo('2026-11-20 12:00:00');
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Riwayat',
            'sell_price' => 100000,
            'cost_price' => 30000,
            'stock' => 10,
        ]);
        $saleService = app(SaleService::class);
        $returnService = app(ReturnService::class);

        $partiallyReturnedSale = $saleService->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
        ]);
        $returnService->create($user, $partiallyReturnedSale->id, $product->id, 1);

        $fullyReturnedSale = $saleService->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'payments' => [['method' => 'cash', 'amount' => 100000, 'cash_account_id' => $cashAccountId]],
        ]);
        $returnService->create($user, $fullyReturnedSale->id, $product->id, 1);

        $unreturnedSale = $saleService->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'payments' => [['method' => 'cash', 'amount' => 100000, 'cash_account_id' => $cashAccountId]],
        ]);

        Livewire::test(History::class)
            ->assertViewHas('summary', fn (array $summary): bool => $summary['count'] === 3
                && $summary['revenue'] === 200000.0
                && $summary['paid'] === 200000.0
                && $summary['refund'] === 200000.0
                && $summary['outstanding'] === 0.0
                && $summary['lunas'] === 2)
            ->assertSee($partiallyReturnedSale->code)
            ->assertSee($fullyReturnedSale->code)
            ->assertSee($unreturnedSale->code)
            ->assertSee('Retur sebagian')
            ->assertSee('Diretur')
            ->assertSee('Rp 100.000')
            ->assertSee('Rp 0');
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create(['email' => 'sales-history-owner@example.test']);
        $store = Store::create([
            'name' => 'Toko Riwayat Penjualan',
            'slug' => 'toko-riwayat-penjualan',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }
}
