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
        Schema::create('branch_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();

            // Ensure one stock record per product per branch
            $table->unique(['branch_id', 'product_id']);

            // Indexes for performance
            $table->index('branch_id');
            $table->index('product_id');
            $table->index('quantity'); // For low stock queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_stocks');
    }
};
