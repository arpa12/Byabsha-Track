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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('category')->nullable(); // rent, salary, utilities, etc.
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('branch_id');
            $table->index('user_id');
            $table->index('expense_date');
            $table->index('category');
            $table->index(['branch_id', 'expense_date']); // For branch expense reports
            $table->index(['category', 'expense_date']); // For category-wise reports
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
