<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('conducted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code');
            $table->date('conducted_at');
            $table->string('status', 20)->default('draft'); // draft, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'status']);
        });

        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->integer('system_qty')->default(0);
            $table->integer('counted_qty')->nullable();
            $table->integer('diff')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'stock_opname_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
        Schema::dropIfExists('stock_opnames');
    }
};
