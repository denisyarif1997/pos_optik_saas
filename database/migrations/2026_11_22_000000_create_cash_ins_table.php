<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('received_at');
            $table->string('category', 60);
            $table->string('description', 255);
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->index(['store_id', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_ins');
    }
};
