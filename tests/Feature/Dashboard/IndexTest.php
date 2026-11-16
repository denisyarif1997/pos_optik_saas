<?php

namespace Tests\Feature\Dashboard;

use App\Livewire\Dashboard\Index as DashboardIndex;
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

    public function test_recent_transactions_show_net_total_and_return_status(): void
    {
        $user = User::factory()->create(['email' => 'dashboard-return@example.test']);
        $store = Store::create([
            'name' => 'Toko Dashboard Retur',
            'slug' => 'toko-dashboard-retur',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);
        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Dashboard',
            'sell_price' => 100000,
            'stock' => 5,
        ]);
        $sale = app(SaleService::class)->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
        ]);
        app(ReturnService::class)->create($user, $sale->id, $product->id, 1);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee($sale->code)
            ->assertSee('Rp 100.000')
            ->assertSee('Lunas')
            ->assertSee('Retur sebagian');
    }

    public function test_daily_sales_and_weekly_chart_subtract_approved_returns(): void
    {
        $this->travelTo('2026-11-15 12:00:00');
        $user = User::factory()->create(['email' => 'dashboard-daily-return@example.test']);
        $store = Store::create([
            'name' => 'Toko Penjualan Harian',
            'slug' => 'toko-penjualan-harian',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);
        $cashAccountId = CashAccount::forStore($store->id)->where('code', 'kas-toko')->value('id');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Harian',
            'sell_price' => 100000,
            'stock' => 10,
        ]);
        $saleService = app(SaleService::class);
        $returnService = app(ReturnService::class);

        $this->travelTo('2026-11-14 12:00:00');
        $saleService->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'payments' => [['method' => 'cash', 'amount' => 100000, 'cash_account_id' => $cashAccountId]],
        ]);

        $this->travelTo('2026-11-15 12:00:00');
        $sale = $saleService->create($user, [
            'items' => [['product_id' => $product->id, 'qty' => 2]],
            'payments' => [['method' => 'cash', 'amount' => 200000, 'cash_account_id' => $cashAccountId]],
        ]);
        $returnService->create($user, $sale->id, $product->id, 1);

        Livewire::test(DashboardIndex::class)
            ->assertViewHas('todaySales', 100000.0)
            ->assertViewHas('growth', 0.0)
            ->assertViewHas('weekly', fn (array $weekly): bool => $weekly[5]['value'] === 100000.0
                && $weekly[6]['value'] === 100000.0);
    }
}
