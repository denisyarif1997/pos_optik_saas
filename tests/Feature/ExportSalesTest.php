<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_export_subtracts_approved_returns(): void
    {
        $this->travelTo('2026-11-20 12:00:00');
        $user = User::factory()->create(['email' => 'export-owner@example.test']);
        $store = Store::create([
            'name' => 'Toko Export',
            'slug' => 'toko-export',
        ]);
        $store->members()->attach($user->id, ['role' => 'owner', 'is_active' => true]);
        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Frame Export',
            'sell_price' => 100000,
            'stock' => 10,
        ]);
        $sale = Sale::create([
            'store_id' => $store->id,
            'cashier_id' => $user->id,
            'code' => 'RET-CSV-1',
            'sold_at' => now(),
            'subtotal' => 200000,
            'total' => 200000,
            'paid_total' => 200000,
            'payment_status' => 'lunas',
            'status' => 'completed',
        ]);
        SaleItem::create([
            'store_id' => $store->id,
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 2,
            'price' => 100000,
            'subtotal' => 200000,
        ]);
        ReturnModel::create([
            'store_id' => $store->id,
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'created_by' => $user->id,
            'qty' => 1,
            'refund_amount' => 100000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get(route('export.sales', [
            'from' => '2026-11-01',
            'to' => '2026-11-30',
        ]));

        $response->assertDownload('penjualan-2026-11-01-2026-11-30.csv')
            ->assertStreamedContent(
                "Tanggal,Jenis,Kode,Pelanggan,Retur,Total,Dibayar,Status\n".
                "\"2026-11-20 12:00\",Penjualan,RET-CSV-1,Umum,100000,100000,100000,lunas\n",
            );
    }
}
