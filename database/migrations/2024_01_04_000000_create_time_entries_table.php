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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('duration', 5, 2)->storedAs('TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('task_id', 'idx_time_entries_task');
            $table->index('user_id', 'idx_time_entries_user');
            $table->index(['start_time', 'end_time'], 'idx_time_entries_dates');

            // Check constraint for time entries
            $table->rawCheck('end_time > start_time', 'chk_time_entry_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};