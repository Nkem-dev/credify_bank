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
        Schema::create('nigerian_banks', function (Blueprint $table) {
            $table->char('code', 3)->primary(); // e.g., '044' for Access Bank
            $table->string('name'); // e.g., 'Access Bank Plc'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nigerian_banks');
    }
};
