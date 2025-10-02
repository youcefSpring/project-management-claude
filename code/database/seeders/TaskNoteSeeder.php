<?php

namespace Database\Seeders;

use App\Models\TaskNote;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskNoteSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        if ($tasks->isEmpty() || $users->isEmpty()) {
            return;
        }

        $sampleNotes = [
            'Initial analysis completed. Moving to next phase.',
            'Client feedback received and incorporated.',
            'Need to review API documentation before proceeding.',
            'Meeting scheduled with stakeholders for tomorrow.',
            'Code review completed with minor suggestions.',
            'Testing phase started. Found a few minor issues.',
            'All requirements have been implemented.',
            'Waiting for client approval on design changes.',
            'Performance optimization completed.',
            'Documentation updated with latest changes.',
        ];

        // Create random notes for tasks
        foreach ($tasks->take(10) as $task) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                TaskNote::create([
                    'task_id' => $task->id,
                    'user_id' => $users->random()->id,
                    'content' => $sampleNotes[array_rand($sampleNotes)],
                ]);
            }
        }
    }
}