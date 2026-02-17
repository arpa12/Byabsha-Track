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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Salesman
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->date('sale_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('paid');
            $table->enum('payment_method', ['cash', 'card', 'mobile_banking', 'bank_transfer'])->default('cash');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('invoice_no');
            $table->index('branch_id');
            $table->index('user_id');
            $table->index('sale_date');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('customer_phone');
            $table->index(['branch_id', 'sale_date']); // Composite index for daily reports
            $table->index(['user_id', 'sale_date']); // For salesman performance reports
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
