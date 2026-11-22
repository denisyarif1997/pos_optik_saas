<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('sph', 4, 2)->nullable()->after('product_id');
            $table->decimal('cyl', 4, 2)->nullable()->after('sph');
            $table->decimal('add_val', 4, 2)->default(0.00)->after('cyl');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('sph', 4, 2)->nullable()->after('product_id');
            $table->decimal('cyl', 4, 2)->nullable()->after('sph');
            $table->decimal('add_val', 4, 2)->default(0.00)->after('cyl');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['sph', 'cyl', 'add_val']);
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['sph', 'cyl', 'add_val']);
        });
    }
};
