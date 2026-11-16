<?php

namespace Tests\Feature\Returns;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Services\ReturnService;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReturnTaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_returns_refund_discounted_item_value_and_proportional_ppn(): void
    {
        $user = User::factory()->create();
        $store = Store::create([
            'name' => 'Toko Retur PPN',
            'slug' => 'toko-retur-ppn',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $this->actingAs($user);

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Retur',
            'sell_price' => 100000,
            'cost_price' => 30000,
            'stock' => 10,
        ]);
        $sale = app(SaleService::class)->create($user, [
            'discount_percent' => 10,
            'apply_ppn' => true,
            'items' => [['product_id' => $product->id, 'qty' => 2, 'discount' => 10000]],
        ]);
        $returns = app(ReturnService::class);

        $firstReturn = $returns->create($user, $sale->id, $product->id, 1);
        $secondReturn = $returns->create($user, $sale->id, $product->id, 1);

        $this->assertSame(85500.0, (float) $firstReturn->item_refund_amount);
        $this->assertSame(9405.0, (float) $firstReturn->ppn_refund_amount);
        $this->assertSame(94905.0, (float) $firstReturn->refund_amount);
        $this->assertSame(189810.0, (float) $firstReturn->refund_amount + (float) $secondReturn->refund_amount);
    }
}
