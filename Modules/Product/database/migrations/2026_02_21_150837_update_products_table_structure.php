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
        Schema::table('products', function (Blueprint $table) {
            // Add new columns
            $table->string('category')->nullable()->after('name');
            $table->string('brand')->nullable()->after('category');

            // Rename columns
            $table->renameColumn('cost', 'purchase_price');
            $table->renameColumn('price', 'sale_price');

            // Drop columns we don't need
            $table->dropColumn(['sku', 'description', 'unit', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the rename
            $table->renameColumn('purchase_price', 'cost');
            $table->renameColumn('sale_price', 'price');

            // Remove new columns
            $table->dropColumn(['category', 'brand']);

            // Add back old columns
            $table->string('sku')->unique()->after('name');
            $table->text('description')->nullable()->after('sku');
            $table->string('unit')->default('pcs')->after('stock_quantity');
            $table->boolean('is_active')->default(true)->after('shop_id');
        });
    }
};
