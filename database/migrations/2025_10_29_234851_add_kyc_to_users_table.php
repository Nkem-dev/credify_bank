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
        $table->enum('kyc_tier', [1, 2, 3])->default(1)->after('email_verified_at');
        $table->string('nin', 11)->nullable()->unique()->after('kyc_tier');
        $table->text('address')->nullable()->after('nin');
        $table->string('address_proof')->nullable()->after('address');
        $table->timestamp('kyc_verified_at')->nullable()->after('address_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kyc_tier', 'nin', 'address', 'address_proof', 'kyc_verified_at']);
        });
    }
};
