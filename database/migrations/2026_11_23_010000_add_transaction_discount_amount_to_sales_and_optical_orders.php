<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_amount', 14, 2)->default(0)->after('discount_percent');
        });

        Schema::table('optical_orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 14, 2)->default(0)->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });

        Schema::table('optical_orders', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};
