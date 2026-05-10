<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $manager = User::where('role', 'manager')->first();

        $projects = [
            [
                'title' => 'E-commerce Website Redesign',
                'description' => 'Complete redesign of the company e-commerce website with modern UI/UX and improved performance.',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'active',
                'manager_id' => $manager->id,
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Native iOS and Android app for customer engagement and loyalty program.',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(90),
                'status' => 'active',
                'manager_id' => $manager->id,
            ],
            [
                'title' => 'Internal CRM System',
                'description' => 'Custom CRM system for managing customer relationships and sales pipeline.',
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
                'manager_id' => $manager->id,
            ],
            [
                'title' => 'Data Analytics Dashboard',
                'description' => 'Business intelligence dashboard for real-time data visualization and reporting.',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(75),
                'status' => 'active',
                'manager_id' => $manager->id,
            ],
            [
                'title' => 'Legacy System Migration',
                'description' => 'Migration of legacy systems to modern cloud-based infrastructure.',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(180),
                'status' => 'active',
                'manager_id' => $manager->id,
            ],
            [
                'title' => 'Website Maintenance',
                'description' => 'Ongoing maintenance and updates for company website.',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(10),
                'status' => 'completed',
                'manager_id' => $manager->id,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }
    }
}
