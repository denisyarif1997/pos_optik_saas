<?php

namespace Tests\Feature\Reports;

use App\Livewire\Reports\Index as ReportIndex;
use App\Models\CashAccount;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_nets_returns_against_the_original_sale(): void
    {
        $this->travelTo('2026-11-20 12:00:00');
        [$user, $store] = $this->makeStoreWithOwner();
        $this->actingAs($user);
        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Retur',
            'sell_price' => 100000,
            'cost_price' => 30000,
            'stock' => 10,
        ]);
        $sale = app(SaleService::class)->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
        ]);

        $this->travelTo('2026-12-05 09:00:00');
        app(ReturnService::class)->create($user, $sale->id, $product->id, 1, 'Retur sebagian');

        Livewire::test(ReportIndex::class)
            ->set('from', '2026-11-01')
            ->set('to', '2026-11-30')
            ->assertViewHas('saleRevenue', 100000.0)
            ->assertViewHas('refundTotal', 100000.0)
            ->assertViewHas('collected', 100000.0)
            ->assertViewHas('cogs', 30000.0)
            ->assertViewHas('grossProfit', 70000.0)
            ->assertViewHas('topProducts', fn ($products): bool => $products->count() === 1
                && (int) $products->first()->qty === 1
                && (float) $products->first()->omzet === 100000.0)
            ->assertViewHas('dailyTrend', fn (array $days): bool => $days[0]['sale'] === 100000.0
                && $days[0]['count'] === 1)
            ->assertViewHas('recentTransactions', fn ($transactions): bool => $transactions->first()['total'] === 100000.0
                && $transactions->first()['paid'] === 100000.0)
            ->assertSee('retur Rp 100.000');
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(): array
    {
        $user = User::factory()->create(['email' => 'report-owner@example.test']);
        $store = Store::create([
            'name' => 'Toko Laporan',
            'slug' => 'toko-laporan',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }
}
