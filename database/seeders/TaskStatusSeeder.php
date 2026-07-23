<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Seed the default task statuses for every organization.
     */
    public function run(): void
    {
        foreach (Organization::withTrashed()->pluck('id') as $organizationId) {
            TaskStatus::seedDefaultsFor($organizationId);
        }
    }
}
