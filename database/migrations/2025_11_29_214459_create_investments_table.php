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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_invested', 15, 2)->default(0); // Total amount user has invested
            $table->decimal('current_value', 15, 2)->default(0); // Current portfolio value
            $table->decimal('total_profit_loss', 15, 2)->default(0); // Overall profit/loss
            $table->decimal('profit_loss_percentage', 8, 4)->default(0); // Overall profit/loss %
            $table->decimal('total_dividends', 15, 2)->default(0); // Total dividends earned
            $table->decimal('ytd_return', 8, 4)->default(0); // Year-to-date return %
            $table->timestamps();

              // One investment record per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
