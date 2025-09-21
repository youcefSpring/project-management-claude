<?php

namespace Database\Seeders;

use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TimeEntrySeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::where('role', 'member')->get();

        if ($tasks->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Create sample time entries for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $task = $tasks->random();
            $user = $users->random();
            $date = Carbon::now()->subDays(rand(0, 30));

            TimeEntry::create([
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'user_id' => $user->id,
                'date' => $date->toDateString(),
                'start_time' => $date->setTime(rand(8, 16), rand(0, 59)),
                'end_time' => $date->copy()->addHours(rand(1, 4))->addMinutes(rand(0, 59)),
                'duration' => rand(1, 8) + (rand(0, 59) / 60), // Random hours with minutes
                'description' => 'Working on ' . $task->title,
                'is_billable' => rand(0, 1),
            ]);
        }
    }
}