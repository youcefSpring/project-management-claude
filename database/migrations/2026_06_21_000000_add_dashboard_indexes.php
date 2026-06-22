<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes backing the dashboard aggregate queries.
     */
    public function up(): void
    {
        $this->addIndex('tasks', 'due_date', 'tasks_due_date_index');
        $this->addIndex('tasks', 'status', 'tasks_status_index');
        $this->addIndex('time_entries', 'start_time', 'time_entries_start_time_index');
        $this->addIndex('projects', 'status', 'projects_status_index');
    }

    public function down(): void
    {
        $this->dropIndex('tasks', 'tasks_due_date_index');
        $this->dropIndex('tasks', 'tasks_status_index');
        $this->dropIndex('time_entries', 'time_entries_start_time_index');
        $this->dropIndex('projects', 'projects_status_index');
    }

    private function addIndex(string $table, string $column, string $name): void
    {
        if (! Schema::hasColumn($table, $column)) {
            return;
        }

        $exists = collect(Schema::getIndexes($table))->contains(fn ($i) => $i['name'] === $name);
        if ($exists) {
            return;
        }

        Schema::table($table, fn (Blueprint $t) => $t->index($column, $name));
    }

    private function dropIndex(string $table, string $name): void
    {
        $exists = collect(Schema::getIndexes($table))->contains(fn ($i) => $i['name'] === $name);
        if ($exists) {
            Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
        }
    }
};
