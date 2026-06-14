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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Opened by
            $table->decimal('opening_balance', 12, 2)->default(0.00);
            $table->decimal('expected_balance', 12, 2)->nullable();
            $table->decimal('actual_balance', 12, 2)->nullable();
            $table->decimal('cash_in_hand', 12, 2)->nullable();
            $table->decimal('discrepancy', 12, 2)->nullable();
            $table->string('status', 20)->default('open'); // open, closed
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ledger_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('register_id')->constrained('cash_registers')->cascadeOnDelete();
            $table->string('type', 20); // income, expense
            $table->string('category', 100); // e.g.,POS Sale, Utility, food, Payout
            $table->decimal('amount', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('accounts_receivable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('register_id')->constrained('cash_registers')->cascadeOnDelete();
            $table->string('customer_name', 255);
            $table->string('customer_phone', 30)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending'); // pending, repaid
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receivable');
        Schema::dropIfExists('ledger_transactions');
        Schema::dropIfExists('cash_registers');
    }
};
