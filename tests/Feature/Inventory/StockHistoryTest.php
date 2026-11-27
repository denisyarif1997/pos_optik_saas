<?php

namespace Tests\Feature\Inventory;

use App\Livewire\Inventory\StockHistory;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StockHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_history_displays_and_filters_store_movements(): void
    {
        [$owner, $store] = $this->makeStoreWithOwner('Toko Histori', 'toko-histori@example.test');
        [$otherOwner, $otherStore] = $this->makeStoreWithOwner('Toko Lain', 'toko-lain@example.test');

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Lensa Blueray 1.56',
            'sku' => 'LNS-BR-56',
            'type' => 'STOCK_LENS',
            'sell_price' => 150000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $otherProduct = Product::create([
            'store_id' => $otherStore->id,
            'name' => 'Frame Rahasia Toko Lain',
            'sku' => 'FRM-SECRET',
            'type' => 'FRAME',
            'sell_price' => 200000,
            'stock' => 5,
        ]);

        $this->actingAs($otherOwner);
        app(StockService::class)->adjust($otherOwner, $otherProduct->id, 3, 'in', 'Stok toko lain');

        $this->actingAs($owner);
        $stockService = app(StockService::class);
        $stockService->adjust($owner, $product->id, 5, 'in', 'Kulak supplier optik');
        $stockService->adjust($owner, $product->id, -2, 'out', 'Terjual ke pelanggan');

        $this->get(route('inventory.stock-history'))
            ->assertOk()
            ->assertSee('Laporan Histori Stok')
            ->assertSee('Lensa Blueray 1.56')
            ->assertSee('Kulak supplier optik')
            ->assertSee('Terjual ke pelanggan')
            ->assertDontSee('Frame Rahasia Toko Lain');

        Livewire::test(StockHistory::class)
            ->assertViewHas('summary', fn (array $summary): bool => $summary['count'] === 2
                && $summary['in'] === 5
                && $summary['out'] === 2
                && $summary['net'] === 3)
            ->set('type', 'in')
            ->assertSee('Kulak supplier optik')
            ->assertDontSee('Terjual ke pelanggan')
            ->set('type', '')
            ->set('search', 'pelanggan')
            ->assertSee('Terjual ke pelanggan')
            ->assertDontSee('Kulak supplier optik');

        $this->assertDatabaseHas('stock_movements', [
            'store_id' => $store->id,
            'product_id' => $product->id,
            'type' => 'in',
            'qty' => 5,
            'stock_before' => 10,
            'stock_after' => 15,
        ]);

        $this->assertSame(1, StockMovement::where('store_id', $otherStore->id)->count());
    }

    public function test_stock_history_csv_export_downloads_movements(): void
    {
        [$owner, $store] = $this->makeStoreWithOwner('Toko Export Stok', 'export-stok@example.test');
        $this->actingAs($owner);

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Titanium',
            'sku' => 'FRM-TI-01',
            'type' => 'FRAME',
            'sell_price' => 350000,
            'stock' => 4,
        ]);

        app(StockService::class)->adjust($owner, $product->id, 6, 'in', 'Restok awal bulan');

        $response = $this->get(route('export.stock-history'));
        $response->assertOk();
        $content = $response->streamedContent();

        $this->assertStringContainsString('Frame Titanium', $content);
        $this->assertStringContainsString('FRM-TI-01', $content);
        $this->assertStringContainsString('Restok awal bulan', $content);
    }

    /**
     * @return array{User, Store}
     */
    private function makeStoreWithOwner(string $storeName, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $store = Store::create([
            'name' => $storeName,
            'slug' => str()->slug($storeName).'-'.uniqid(),
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);

        return [$user->fresh(), $store];
    }
}
