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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('biometric_enabled')->default(false);
            $table->json('security_questions')->nullable();
            $table->integer('session_timeout')->default(30);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('push_notifications')->default(true);
            $table->json('notification_preferences')->nullable();
            $table->boolean('hide_balance')->default(false);
            $table->enum('language', ['en', 'yo', 'ig', 'ha'])->default('en');
            $table->enum('theme', ['light', 'dark', 'system'])->default('system');
            $table->string('currency_format')->default('NGN');
            $table->boolean('account_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->text('closure_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
