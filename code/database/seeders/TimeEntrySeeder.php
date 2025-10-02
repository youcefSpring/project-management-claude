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
        // Track user schedules to avoid overlaps
        $userSchedules = [];

        for ($i = 0; $i < 50; $i++) {
            $task = $tasks->random();
            $user = $users->random();
            $date = Carbon::now()->subDays(rand(1, 30)); // Start from yesterday to avoid future dates

            // Try to find a non-overlapping time slot
            $attempts = 0;
            do {
                $startHour = rand(8, 15); // 8 AM to 3 PM start times
                $startTime = $date->copy()->setTime($startHour, rand(0, 59));
                $endTime = $startTime->copy()->addHours(rand(1, 3))->addMinutes(rand(0, 59));

                $hasOverlap = false;
                if (isset($userSchedules[$user->id])) {
                    foreach ($userSchedules[$user->id] as $existingEntry) {
                        if (($startTime >= $existingEntry['start'] && $startTime < $existingEntry['end']) ||
                            ($endTime > $existingEntry['start'] && $endTime <= $existingEntry['end']) ||
                            ($startTime <= $existingEntry['start'] && $endTime >= $existingEntry['end'])) {
                            $hasOverlap = true;
                            break;
                        }
                    }
                }

                $attempts++;
                if ($attempts > 10) break; // Avoid infinite loop

            } while ($hasOverlap);

            if (!$hasOverlap) {
                // Store the schedule
                if (!isset($userSchedules[$user->id])) {
                    $userSchedules[$user->id] = [];
                }
                $userSchedules[$user->id][] = ['start' => $startTime, 'end' => $endTime];

                TimeEntry::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'comment' => 'Working on ' . $task->title,
                ]);
            }
        }
    }
}