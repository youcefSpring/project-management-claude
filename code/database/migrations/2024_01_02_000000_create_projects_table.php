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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['en_cours', 'terminé', 'annulé'])->default('en_cours');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('manager_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes
            $table->index('status', 'idx_projects_status');
            $table->index('manager_id', 'idx_projects_manager');
            $table->index(['start_date', 'end_date'], 'idx_projects_dates');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
