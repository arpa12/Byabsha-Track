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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_cost', 15, 2); // Purchase price at time of sale for profit calculation
            $table->decimal('unit_price', 15, 2); // Selling price
            $table->decimal('subtotal', 15, 2);
            $table->decimal('profit', 15, 2)->default(0); // Calculated profit per item
            $table->timestamps();

            // Indexes for performance
            $table->index('sale_id');
            $table->index('product_id');
            $table->index('profit'); // For profit reports
            $table->index(['sale_id', 'product_id']); // For item lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
