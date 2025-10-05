<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::where('role', 'member')->get();

        if ($projects->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Create sample tasks for each project
        foreach ($projects as $project) {
            $taskCount = rand(3, 8); // Random number of tasks per project

            for ($i = 0; $i < $taskCount; $i++) {
                $statuses = ['pending', 'in_progress', 'completed'];
                $status = $statuses[array_rand($statuses)];

                $dueDate = null;
                if (rand(0, 1)) { // 50% chance of having a due date
                    $dueDate = Carbon::now()->addDays(rand(-10, 30));
                }

                $assignedTo = null;
                if (rand(0, 1)) { // 50% chance of being assigned
                    $assignedTo = $users->random()->id;
                }

                Task::create([
                    'project_id' => $project->id,
                    'title' => $this->getRandomTaskTitle(),
                    'description' => $this->getRandomTaskDescription(),
                    'status' => $status,
                    'due_date' => $dueDate,
                    'assigned_to' => $assignedTo,
                ]);
            }
        }
    }

    private function getRandomTaskTitle(): string
    {
        $titles = [
            'Design User Interface',
            'Implement Authentication',
            'Create Database Schema',
            'Write Unit Tests',
            'Setup CI/CD Pipeline',
            'Optimize Performance',
            'Fix Bug Reports',
            'Update Documentation',
            'Code Review',
            'Deploy to Production',
            'Research Technologies',
            'Create Wireframes',
            'Frontend Development',
            'Backend Development',
            'Integration Testing',
            'User Acceptance Testing',
            'Security Audit',
            'Data Migration',
            'API Development',
            'Mobile Optimization',
        ];

        return $titles[array_rand($titles)];
    }

    private function getRandomTaskDescription(): string
    {
        $descriptions = [
            'This task involves implementing the required functionality according to specifications.',
            'Complete this task following best practices and coding standards.',
            'Ensure all requirements are met and properly tested.',
            'Work on this task with attention to detail and quality.',
            'Implement this feature with proper error handling and validation.',
            'Complete this development task following the project guidelines.',
            'Test and validate the implementation thoroughly.',
            'Document the implementation and update relevant documentation.',
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
