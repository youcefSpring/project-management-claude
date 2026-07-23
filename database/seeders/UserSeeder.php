<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Every demo user belongs to the same organization
        $organization = Organization::firstOrCreate(
            ['name' => 'Demo Organization'],
            [
                'description' => 'Default organization for the demo users',
                'is_active' => true,
            ]
        );

        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'language' => 'en',
            'email_verified_at' => now(),
            'organization_id' => $organization->id,
        ]);

        // Create project manager
        User::create([
            'name' => 'Project Manager',
            'email' => 'manager@demo.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'language' => 'en',
            'email_verified_at' => now(),
            'organization_id' => $organization->id,
        ]);

        // Create team members
        $teamMembers = [
            [
                'name' => 'Demo Member',
                'email' => 'member@demo.com',
                'role' => 'member',
            ],
            [
                'name' => 'John Developer',
                'email' => 'john@demo.com',
                'role' => 'member',
            ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@demo.com',
                'role' => 'member',
            ],
            [
                'name' => 'Mike Tester',
                'email' => 'mike@demo.com',
                'role' => 'member',
            ],
            [
                'name' => 'Sarah Frontend',
                'email' => 'sarah@demo.com',
                'role' => 'member',
            ],
        ];

        foreach ($teamMembers as $member) {
            User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password'),
                'role' => $member['role'],
                'language' => 'en',
                'email_verified_at' => now(),
                'organization_id' => $organization->id,
            ]);
        }

        // Create another member user
        User::create([
            'name' => 'Client User',
            'email' => 'client@demo.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'language' => 'en',
            'email_verified_at' => now(),
            'organization_id' => $organization->id,
        ]);
    }
}
