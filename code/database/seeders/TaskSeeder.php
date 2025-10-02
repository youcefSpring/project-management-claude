<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        // E-commerce Website Redesign tasks
        $ecommerceProject = $projects->where('name', 'E-commerce Website Redesign')->first();
        if ($ecommerceProject) {
            $ecommerceTasks = [
                [
                    'title' => 'UI/UX Design Research',
                    'description' => 'Research current design trends and analyze competitor websites.',
                    'status' => 'completed',
                    'priority' => 'high',
                    'estimated_hours' => 40,
                    'due_date' => Carbon::now()->subDays(20),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Create Wireframes',
                    'description' => 'Design wireframes for all major pages of the website.',
                    'status' => 'completed',
                    'priority' => 'high',
                    'estimated_hours' => 32,
                    'due_date' => Carbon::now()->subDays(15),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Frontend Development',
                    'description' => 'Implement responsive frontend based on approved designs.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'estimated_hours' => 80,
                    'due_date' => Carbon::now()->addDays(10),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Backend API Development',
                    'description' => 'Develop RESTful APIs for product management and user authentication.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'estimated_hours' => 60,
                    'due_date' => Carbon::now()->addDays(15),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Payment Integration',
                    'description' => 'Integrate payment gateways (Stripe, PayPal) for checkout process.',
                    'status' => 'pending',
                    'priority' => 'high',
                    'estimated_hours' => 24,
                    'due_date' => Carbon::now()->addDays(25),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Quality Assurance Testing',
                    'description' => 'Comprehensive testing of all features and functionality.',
                    'status' => 'pending',
                    'priority' => 'medium',
                    'estimated_hours' => 40,
                    'due_date' => Carbon::now()->addDays(35),
                    'assigned_to' => $users->random()->id,
                ],
            ];

            foreach ($ecommerceTasks as $taskData) {
                $taskData['project_id'] = $ecommerceProject->id;
                Task::create($taskData);
            }
        }

        // Mobile App Development tasks
        $mobileProject = $projects->where('name', 'Mobile App Development')->first();
        if ($mobileProject) {
            $mobileTasks = [
                [
                    'title' => 'App Architecture Planning',
                    'description' => 'Define app architecture and technology stack.',
                    'status' => 'completed',
                    'priority' => 'high',
                    'estimated_hours' => 16,
                    'due_date' => Carbon::now()->subDays(10),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'iOS Development',
                    'description' => 'Develop native iOS application with Swift.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'estimated_hours' => 120,
                    'due_date' => Carbon::now()->addDays(45),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Android Development',
                    'description' => 'Develop native Android application with Kotlin.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'estimated_hours' => 120,
                    'due_date' => Carbon::now()->addDays(45),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Push Notifications Setup',
                    'description' => 'Implement push notification system for user engagement.',
                    'status' => 'pending',
                    'priority' => 'medium',
                    'estimated_hours' => 20,
                    'due_date' => Carbon::now()->addDays(60),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'App Store Submission',
                    'description' => 'Prepare and submit apps to App Store and Google Play.',
                    'status' => 'pending',
                    'priority' => 'low',
                    'estimated_hours' => 12,
                    'due_date' => Carbon::now()->addDays(80),
                    'assigned_to' => $users->random()->id,
                ],
            ];

            foreach ($mobileTasks as $taskData) {
                $taskData['project_id'] = $mobileProject->id;
                Task::create($taskData);
            }
        }

        // Internal CRM System tasks
        $crmProject = $projects->where('name', 'Internal CRM System')->first();
        if ($crmProject) {
            $crmTasks = [
                [
                    'title' => 'Requirements Analysis',
                    'description' => 'Gather and analyze business requirements for CRM system.',
                    'status' => 'completed',
                    'priority' => 'high',
                    'estimated_hours' => 24,
                    'due_date' => Carbon::now()->subDays(30),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Database Design',
                    'description' => 'Design database schema for customer and sales data.',
                    'status' => 'completed',
                    'priority' => 'high',
                    'estimated_hours' => 20,
                    'due_date' => Carbon::now()->subDays(25),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'User Interface Development',
                    'description' => 'Create intuitive user interface for CRM features.',
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'estimated_hours' => 40,
                    'due_date' => Carbon::now()->addDays(5),
                    'assigned_to' => $users->random()->id,
                ],
                [
                    'title' => 'Reporting Module',
                    'description' => 'Develop comprehensive reporting and analytics module.',
                    'status' => 'pending',
                    'priority' => 'medium',
                    'estimated_hours' => 32,
                    'due_date' => Carbon::now()->addDays(20),
                    'assigned_to' => $users->random()->id,
                ],
            ];

            foreach ($crmTasks as $taskData) {
                $taskData['project_id'] = $crmProject->id;
                Task::create($taskData);
            }
        }

        // Add some overdue tasks for testing
        $overdueProject = $projects->first();
        if ($overdueProject) {
            Task::create([
                'project_id' => $overdueProject->id,
                'title' => 'Overdue Task Example',
                'description' => 'This task is overdue for testing purposes.',
                'status' => 'pending',
                'priority' => 'high',
                'estimated_hours' => 8,
                'due_date' => Carbon::now()->subDays(5),
                'assigned_to' => $users->random()->id,
            ]);
        }
    }
}