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
        Schema::table('task_notes', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('content');
            $table->boolean('is_internal')->default(false)->after('attachments');
            $table->string('type')->default('comment')->after('is_internal'); // comment, status_change, attachment
            $table->json('metadata')->nullable()->after('type'); // Additional data for different note types

            // Indexes
            $table->index('type', 'idx_task_notes_type');
            $table->index('is_internal', 'idx_task_notes_internal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_notes', function (Blueprint $table) {
            $table->dropIndex('idx_task_notes_type');
            $table->dropIndex('idx_task_notes_internal');
            $table->dropColumn(['attachments', 'is_internal', 'type', 'metadata']);
        });
    }
};
