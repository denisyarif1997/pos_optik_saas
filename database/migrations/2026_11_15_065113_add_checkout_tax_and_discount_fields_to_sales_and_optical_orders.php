<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('ppn_rate', 5, 2)->default(0);
            $table->decimal('ppn_amount', 14, 2)->default(0);
        });

        Schema::table('optical_orders', function (Blueprint $table) {
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('ppn_rate', 5, 2)->default(0);
            $table->decimal('ppn_amount', 14, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('optical_orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_total', 'discount_percent', 'ppn_rate', 'ppn_amount']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount_percent', 'ppn_rate', 'ppn_amount']);
        });
    }
};
