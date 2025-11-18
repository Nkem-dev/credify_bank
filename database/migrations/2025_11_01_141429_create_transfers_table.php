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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Sender
            $table->string('reference')->unique();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('set null'); // For internal transfers
            $table->char('recipient_account_number', 10);
            $table->string('recipient_name');
            $table->string('recipient_bank_name')->default('Credify Bank');
            $table->char('recipient_bank_code', 3)->nullable();
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2); // amount + fee
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('reference');
            $table->index('user_id');
            $table->index('status');
            $table->index('type');
            $table->index('created_at');

            $table->index(['user_id', 'created_at'], 'idx_user_date');
            $table->index(['status', 'type'], 'idx_status_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
