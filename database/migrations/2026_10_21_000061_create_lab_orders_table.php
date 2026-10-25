<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pelacakan pesanan lensa RX ke laboratorium luar sesuai spesifikasi Lensiro POS.
     * Status: MENUNGGU -> DIPESAN -> DITERIMA, dengan penanda SALAH.
     */
    public function up(): void
    {
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('optical_orders')->cascadeOnDelete();
            $table->string('lab_supplier_name');
            $table->enum('status', ['MENUNGGU', 'DIPESAN', 'DITERIMA', 'SALAH'])->default('MENUNGGU');
            $table->decimal('lab_cost', 14, 2)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'order_id']);
            $table->index(['store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
