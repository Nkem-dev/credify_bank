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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
             $table->string('symbol')->unique(); // e.g., "DANGOTE", "GTB"
            $table->string('name'); // Company name
            $table->string('logo')->nullable(); // Company logo path
            $table->enum('category', [
                'banking',
                'oil_gas',
                'technology',
                'manufacturing',
                'telecommunications',
                'consumer_goods'
            ]);
            $table->decimal('current_price', 15, 2);
            $table->decimal('opening_price', 15, 2)->nullable();
            $table->decimal('day_high', 15, 2)->nullable();
            $table->decimal('day_low', 15, 2)->nullable();
            $table->decimal('week_high', 15, 2)->nullable();
            $table->decimal('week_low', 15, 2)->nullable();
            $table->decimal('market_cap', 20, 2)->nullable();
            $table->bigInteger('volume')->default(0);
            $table->decimal('price_change', 15, 2)->default(0); // Amount change
            $table->decimal('price_change_percentage', 8, 4)->default(0); // Percentage change
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
