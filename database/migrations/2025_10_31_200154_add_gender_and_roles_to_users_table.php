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
        Schema::table('users', function (Blueprint $table) {
        $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
        $table->enum('role', ['admin', 'user', 'customer_care'])->default('user')->change();
        $table->string('transaction_pin')->nullable()->after('password');
        $table->timestamp('pin_set_at')->nullable()->after('transaction_pin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'transaction_pin', 'pin_set_at']);
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};
