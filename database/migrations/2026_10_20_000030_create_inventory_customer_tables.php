<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['store_id', 'name']);
            $table->index(['store_id', 'phone']);
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('checked_at')->nullable();
            $table->decimal('od_sph', 5, 2)->nullable();
            $table->decimal('od_cyl', 5, 2)->nullable();
            $table->integer('od_axis')->nullable();
            $table->decimal('od_add', 5, 2)->nullable();
            $table->decimal('os_sph', 5, 2)->nullable();
            $table->decimal('os_cyl', 5, 2)->nullable();
            $table->integer('os_axis')->nullable();
            $table->decimal('os_add', 5, 2)->nullable();
            $table->decimal('pd', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'customer_id']);
            $table->index(['store_id', 'created_at']);
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('code');
            $table->date('purchased_at');
            $table->decimal('total', 14, 2)->default(0);
            $table->string('status', 30)->default('received');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'purchased_at']);
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->integer('qty');
            $table->decimal('buy_price', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['store_id', 'purchase_id']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('type', 30);
            $table->integer('qty');
            $table->integer('stock_before')->default(0);
            $table->integer('stock_after')->default(0);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'product_id']);
            $table->index(['store_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('customers');
    }
};
