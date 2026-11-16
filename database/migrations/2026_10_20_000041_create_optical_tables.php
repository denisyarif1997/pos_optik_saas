<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('optical_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->string('code');
            $table->string('lab_name')->nullable();
            $table->date('ordered_at')->nullable();
            $table->date('estimated_done_at')->nullable();
            $table->date('taken_at')->nullable();
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('dp_total', 14, 2)->default(0);
            $table->string('status', 30)->default('baru');
            $table->text('work_notes')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'status']);
        });

        Schema::create('optical_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('optical_order_id')->constrained('optical_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_type', 30)->default('frame');
            $table->string('description')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('price', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['store_id', 'optical_order_id']);
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('qty');
            $table->decimal('refund_amount', 14, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status', 30)->default('approved');
            $table->timestamps();

            $table->index(['store_id', 'sale_id']);
        });

        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->foreignId('optical_order_id')->nullable()->constrained('optical_orders')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('code');
            $table->date('valid_until')->nullable();
            $table->text('claim_notes')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('optical_order_items');
        Schema::dropIfExists('optical_orders');
    }
};
