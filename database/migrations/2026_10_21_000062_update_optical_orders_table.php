<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuaikan tabel optical_orders dengan tabel orders di spesifikasi Lensiro POS:
     * tambah order_number, prescription_snapshot (JSON), promised_date, dan
     * sesuaikan status menjadi DRAFT, PESANAN, DIPROSES, SIAP, COMPLETED, BATAL.
     */
    public function up(): void
    {
        // Bersihkan status lama agar valid terhadap enum baru.
        DB::table('optical_orders')
            ->whereNotIn('status', ['DRAFT', 'PESANAN', 'DIPROSES', 'SIAP', 'COMPLETED', 'BATAL'])
            ->update(['status' => 'PESANAN']);

        Schema::table('optical_orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('id');
            $table->json('prescription_snapshot')->nullable()->after('prescription_id');
            $table->date('promised_date')->nullable()->after('dp_total');
            $table->enum('status', [
                'DRAFT', 'PESANAN', 'DIPROSES', 'SIAP', 'COMPLETED', 'BATAL',
            ])->default('PESANAN')->change();
        });

        Schema::table('optical_orders', function (Blueprint $table) {
            $table->unique(['store_id', 'order_number'], 'optical_orders_order_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('optical_orders', function (Blueprint $table) {
            $table->dropUnique('optical_orders_order_number_unique');
            $table->dropColumn(['order_number', 'prescription_snapshot', 'promised_date']);
            $table->string('status', 30)->default('baru')->change();
        });
    }
};
