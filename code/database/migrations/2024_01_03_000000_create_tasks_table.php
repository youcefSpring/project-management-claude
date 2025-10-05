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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['à_faire', 'en_cours', 'fait'])->default('à_faire');
            $table->date('due_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_hours', 8, 2)->default(0.00);
            $table->timestamps();

            // Indexes
            $table->index('project_id', 'idx_tasks_project');
            $table->index('assigned_to', 'idx_tasks_assigned');
            $table->index('status', 'idx_tasks_status');
            $table->index('due_date', 'idx_tasks_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
