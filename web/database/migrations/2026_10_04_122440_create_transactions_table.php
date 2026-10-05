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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Pemasukan, Pengeluaran
            $table->string('category'); // Sewa Kost, Listrik, WiFi, etc.
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable(); // Can store Invoice ID or custom ref
            $table->date('transaction_date');
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
