<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('ppn_rate', 5, 2)->default(0)->after('discount');
            $table->decimal('ppn_amount', 14, 2)->default(0)->after('ppn_rate');
        });

        Schema::table('optical_order_items', function (Blueprint $table) {
            $table->decimal('discount', 14, 2)->default(0)->after('price');
            $table->decimal('ppn_rate', 5, 2)->default(0)->after('discount');
            $table->decimal('ppn_amount', 14, 2)->default(0)->after('ppn_rate');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['ppn_rate', 'ppn_amount']);
        });

        Schema::table('optical_order_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'ppn_rate', 'ppn_amount']);
        });
    }
};
