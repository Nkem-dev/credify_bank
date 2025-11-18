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
        Schema::create('savings_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. "House Rent", "Vacation"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('target_amount', 12, 2)->nullable();
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->decimal('daily_interest_rate', 5, 4)->default(0.0002); // 0.02% daily = ~7.3% yearly
            $table->timestamp('last_interest_applied_at')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_plans');
    }
};
