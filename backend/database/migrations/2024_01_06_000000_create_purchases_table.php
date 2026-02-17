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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Who made the purchase
            $table->date('purchase_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('invoice_no');
            $table->index('branch_id');
            $table->index('supplier_id');
            $table->index('user_id');
            $table->index('purchase_date');
            $table->index('payment_status');
            $table->index(['branch_id', 'purchase_date']); // Composite index for branch reports
            $table->index(['supplier_id', 'payment_status']); // For supplier dues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
