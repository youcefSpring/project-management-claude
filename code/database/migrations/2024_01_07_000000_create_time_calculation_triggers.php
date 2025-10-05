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
        // Trigger pour l'insertion d'une nouvelle entrée de temps
        DB::unprepared('
            CREATE TRIGGER update_task_total_hours_insert
                AFTER INSERT ON time_entries
                FOR EACH ROW
            BEGIN
                UPDATE tasks
                SET total_hours = (
                    SELECT COALESCE(SUM(duration_hours), 0)
                    FROM time_entries
                    WHERE task_id = NEW.task_id
                )
                WHERE id = NEW.task_id;
            END
        ');

        // Trigger pour la mise à jour d'une entrée de temps
        DB::unprepared('
            CREATE TRIGGER update_task_total_hours_update
                AFTER UPDATE ON time_entries
                FOR EACH ROW
            BEGIN
                UPDATE tasks
                SET total_hours = (
                    SELECT COALESCE(SUM(duration_hours), 0)
                    FROM time_entries
                    WHERE task_id = NEW.task_id
                )
                WHERE id = NEW.task_id;

                -- Si task_id a changé, mettre à jour l\'ancienne tâche aussi
                IF OLD.task_id != NEW.task_id THEN
                    UPDATE tasks
                    SET total_hours = (
                        SELECT COALESCE(SUM(duration_hours), 0)
                        FROM time_entries
                        WHERE task_id = OLD.task_id
                    )
                    WHERE id = OLD.task_id;
                END IF;
            END
        ');

        // Trigger pour la suppression d'une entrée de temps
        DB::unprepared('
            CREATE TRIGGER update_task_total_hours_delete
                AFTER DELETE ON time_entries
                FOR EACH ROW
            BEGIN
                UPDATE tasks
                SET total_hours = (
                    SELECT COALESCE(SUM(duration_hours), 0)
                    FROM time_entries
                    WHERE task_id = OLD.task_id
                )
                WHERE id = OLD.task_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_task_total_hours_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS update_task_total_hours_update');
        DB::unprepared('DROP TRIGGER IF EXISTS update_task_total_hours_delete');
    }
};
