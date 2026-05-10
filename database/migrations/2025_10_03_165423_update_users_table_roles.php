<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the ENUM column to include new roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'member', 'developer', 'designer', 'tester', 'hr', 'accountant', 'client') DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'member') DEFAULT 'member'");
    }
};
