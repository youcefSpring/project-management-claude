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
        // First, alter the ENUM column for tasks to include new English values
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('à_faire', 'en_cours', 'fait', 'pending', 'in_progress', 'completed', 'cancelled')");

        // Update Task status values from French to English
        DB::table('tasks')->where('status', 'à_faire')->update(['status' => 'pending']);
        DB::table('tasks')->where('status', 'en_cours')->update(['status' => 'in_progress']);
        DB::table('tasks')->where('status', 'fait')->update(['status' => 'completed']);

        // Remove old French values from ENUM
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");

        // First, alter the ENUM column for projects to include new English values
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('en_cours', 'terminé', 'annulé', 'planning', 'active', 'on_hold', 'completed', 'cancelled')");

        // Update Project status values from French to English
        DB::table('projects')->where('status', 'en_cours')->update(['status' => 'active']);
        DB::table('projects')->where('status', 'terminé')->update(['status' => 'completed']);
        DB::table('projects')->where('status', 'annulé')->update(['status' => 'cancelled']);

        // Remove old French values from ENUM
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('planning', 'active', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, alter the ENUM column for tasks to include old French values
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled', 'à_faire', 'en_cours', 'fait')");

        // Reverse Task status values from English to French
        DB::table('tasks')->where('status', 'pending')->update(['status' => 'à_faire']);
        DB::table('tasks')->where('status', 'in_progress')->update(['status' => 'en_cours']);
        DB::table('tasks')->where('status', 'completed')->update(['status' => 'fait']);

        // Remove English values from ENUM
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('à_faire', 'en_cours', 'fait') DEFAULT 'à_faire'");

        // First, alter the ENUM column for projects to include old French values
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('planning', 'active', 'on_hold', 'completed', 'cancelled', 'en_cours', 'terminé', 'annulé')");

        // Reverse Project status values from English to French
        DB::table('projects')->where('status', 'active')->update(['status' => 'en_cours']);
        DB::table('projects')->where('status', 'completed')->update(['status' => 'terminé']);
        DB::table('projects')->where('status', 'cancelled')->update(['status' => 'annulé']);

        // Remove English values from ENUM
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('en_cours', 'terminé', 'annulé') DEFAULT 'en_cours'");
    }
};
