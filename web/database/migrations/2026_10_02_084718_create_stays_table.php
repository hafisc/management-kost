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
        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('start_at');
            $table->date('end_at');
            $table->string('duration_type'); // daily, weekly, monthly, custom
            $table->integer('duration_value')->nullable();
            $table->string('occupancy_type'); // single, sharing
            $table->string('status')->default('ACTIVE'); // ACTIVE, EXPIRING, EXPIRED, CHECKED_OUT
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('room_id');
            $table->index('status');
            $table->index('end_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stays');
    }
};
