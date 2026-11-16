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
        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('name', 100);
            $table->string('type', 20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['store_id', 'code']);
            $table->unique(['store_id', 'name']);
            $table->index(['store_id', 'is_active']);
        });

        $timestamp = now();
        $accounts = [];
        foreach (DB::table('stores')->pluck('id') as $storeId) {
            $accounts[] = [
                'store_id' => $storeId,
                'code' => 'kas-toko',
                'name' => 'Kas Toko',
                'type' => 'cash',
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
            $accounts[] = [
                'store_id' => $storeId,
                'code' => 'kas-bank',
                'name' => 'Kas Bank',
                'type' => 'bank',
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        if ($accounts !== []) {
            DB::table('cash_accounts')->insert($accounts);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_accounts');
    }
};
