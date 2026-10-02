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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('category', [
                'maintenance',
                'utilities',
                'supplies',
                'repairs',
                'cleaning',
                'security',
                'tax',
                'other',
            ])->default('other');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->foreignId('building_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('receipt')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
