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
            $table->enum('role', ['owner', 'manager', 'salesman'])->default('salesman')->after('email');
            $table->foreignId('branch_id')->nullable()->after('role')->constrained('branches')->onDelete('set null');
            $table->boolean('is_active')->default(true)->after('branch_id');

            // Indexes for performance
            $table->index('role');
            $table->index('branch_id');
            $table->index('is_active');
            $table->index(['branch_id', 'role']); // For branch staff queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'branch_id', 'is_active']);
        });
    }
};
