<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->decimal('item_refund_amount', 14, 2)->default(0);
            $table->decimal('ppn_refund_amount', 14, 2)->default(0);
        });

        DB::table('returns')->update(['item_refund_amount' => DB::raw('refund_amount')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn(['item_refund_amount', 'ppn_refund_amount']);
        });
    }
};
