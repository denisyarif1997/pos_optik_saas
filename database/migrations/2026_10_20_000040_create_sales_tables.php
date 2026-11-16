<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code');
            $table->dateTime('sold_at');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('paid_total', 14, 2)->default(0);
            $table->string('payment_status', 30)->default('unpaid');
            $table->string('status', 30)->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'sold_at']);
            $table->index(['store_id', 'payment_status']);
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->integer('qty');
            $table->decimal('price', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['store_id', 'sale_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('paid_at');
            $table->string('method', 30)->default('cash');
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'sale_id']);
            $table->index(['store_id', 'paid_at']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('spent_at');
            $table->string('category', 60)->default('operasional');
            $table->string('description');
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['store_id', 'spent_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
