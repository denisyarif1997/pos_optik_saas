<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('optical_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('optical_order_id')->constrained('optical_orders')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('paid_at');
            $table->string('method', 30)->default('cash');
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'optical_order_id']);
            $table->index(['store_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('optical_order_payments');
    }
};
