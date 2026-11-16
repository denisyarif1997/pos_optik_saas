<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Matriks stok lensa (SPH x CYL) sesuai spesifikasi Lensiro POS.
     */
    public function up(): void
    {
        Schema::create('stock_lens_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('sph', 4, 2);
            $table->decimal('cyl', 4, 2);
            $table->decimal('add_val', 4, 2)->default(0.00);
            $table->integer('stock_qty')->default(0);
            $table->integer('min_stock_alert')->default(2);
            $table->timestamps();

            $table->unique(['store_id', 'product_id', 'sph', 'cyl', 'add_val']);
            $table->index(['store_id', 'product_id']);
            $table->index(['store_id', 'stock_qty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_lens_matrices');
    }
};
