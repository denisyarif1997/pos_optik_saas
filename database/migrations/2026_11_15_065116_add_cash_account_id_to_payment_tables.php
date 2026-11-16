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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('cash_account_id')->nullable()->constrained('cash_accounts')->nullOnDelete();
        });

        Schema::table('optical_order_payments', function (Blueprint $table) {
            $table->foreignId('cash_account_id')->nullable()->constrained('cash_accounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('optical_order_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cash_account_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cash_account_id');
        });
    }
};
