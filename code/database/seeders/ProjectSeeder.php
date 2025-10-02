<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $manager = User::where('role', 'manager')->first();
        $client = User::where('role', 'client')->first();

        $projects = [
            [
                'name' => 'E-commerce Website Redesign',
                'description' => 'Complete redesign of the company e-commerce website with modern UI/UX and improved performance.',
                'client_name' => 'Tech Solutions Inc.',
                'budget' => 50000.00,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'active',
                'priority' => 'high',
                'created_by' => $admin->id,
                'manager_id' => $manager->id,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Native iOS and Android app for customer engagement and loyalty program.',
                'client_name' => 'Retail Corp',
                'budget' => 75000.00,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(90),
                'status' => 'active',
                'priority' => 'high',
                'created_by' => $manager->id,
                'manager_id' => $manager->id,
            ],
            [
                'name' => 'Internal CRM System',
                'description' => 'Custom CRM system for managing customer relationships and sales pipeline.',
                'client_name' => 'Internal Project',
                'budget' => 30000.00,
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
                'priority' => 'medium',
                'created_by' => $admin->id,
                'manager_id' => $manager->id,
            ],
            [
                'name' => 'Data Analytics Dashboard',
                'description' => 'Business intelligence dashboard for real-time data visualization and reporting.',
                'client_name' => 'Analytics Co.',
                'budget' => 40000.00,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(75),
                'status' => 'active',
                'priority' => 'medium',
                'created_by' => $manager->id,
                'manager_id' => $manager->id,
            ],
            [
                'name' => 'Legacy System Migration',
                'description' => 'Migration of legacy systems to modern cloud-based infrastructure.',
                'client_name' => 'Enterprise Corp',
                'budget' => 100000.00,
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(180),
                'status' => 'planned',
                'priority' => 'high',
                'created_by' => $admin->id,
                'manager_id' => $manager->id,
            ],
            [
                'name' => 'Website Maintenance',
                'description' => 'Ongoing maintenance and updates for company website.',
                'client_name' => 'Various Clients',
                'budget' => 15000.00,
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(10),
                'status' => 'completed',
                'priority' => 'low',
                'created_by' => $manager->id,
                'manager_id' => $manager->id,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }
    }
}