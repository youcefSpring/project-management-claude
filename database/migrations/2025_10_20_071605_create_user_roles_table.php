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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'manager', 'developer', 'designer', 'tester', 'hr', 'accountant', 'client', 'member']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique user-role combinations
            $table->unique(['user_id', 'role']);

            // Index for better performance
            $table->index(['user_id', 'is_active']);
            $table->index(['role', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
