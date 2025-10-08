<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing French status values to English
        DB::table('projects')->where('status', 'en_cours')->update(['status' => 'active']);
        DB::table('projects')->where('status', 'terminé')->update(['status' => 'completed']);
        DB::table('projects')->where('status', 'annulé')->update(['status' => 'cancelled']);

        // Then modify the column to use English enum values
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])
                  ->default('planning')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, revert status values back to French
        DB::table('projects')->where('status', 'active')->update(['status' => 'en_cours']);
        DB::table('projects')->where('status', 'completed')->update(['status' => 'terminé']);
        DB::table('projects')->where('status', 'cancelled')->update(['status' => 'annulé']);

        // Then revert the column to use French enum values
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['en_cours', 'terminé', 'annulé'])
                  ->default('en_cours')
                  ->change();
        });
    }
};
