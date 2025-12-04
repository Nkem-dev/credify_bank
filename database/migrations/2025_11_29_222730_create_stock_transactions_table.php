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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['buy', 'sell']);
            $table->integer('quantity'); // Number of shares
            $table->decimal('price_per_share', 15, 2); // Price at time of transaction
            $table->decimal('total_amount', 15, 2); // Total transaction amount
            $table->decimal('transaction_fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2); // Amount after fees
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
