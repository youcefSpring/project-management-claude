<?php

namespace Database\Seeders;

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
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'language' => 'en',
            'timezone' => 'UTC',
            'hourly_rate' => 75.00,
            'email_verified_at' => now(),
        ]);

        // Create project manager
        User::create([
            'name' => 'Project Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'language' => 'en',
            'timezone' => 'UTC',
            'hourly_rate' => 50.00,
            'email_verified_at' => now(),
        ]);

        // Create team members
        $teamMembers = [
            [
                'name' => 'John Developer',
                'email' => 'john@example.com',
                'role' => 'member',
                'hourly_rate' => 40.00,
            ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@example.com',
                'role' => 'member',
                'hourly_rate' => 45.00,
            ],
            [
                'name' => 'Mike Tester',
                'email' => 'mike@example.com',
                'role' => 'member',
                'hourly_rate' => 35.00,
            ],
            [
                'name' => 'Sarah Frontend',
                'email' => 'sarah@example.com',
                'role' => 'member',
                'hourly_rate' => 42.00,
            ],
        ];

        foreach ($teamMembers as $member) {
            User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password'),
                'role' => $member['role'],
                'language' => 'en',
                'timezone' => 'UTC',
                'hourly_rate' => $member['hourly_rate'],
                'email_verified_at' => now(),
            ]);
        }

        // Create client user
        User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'language' => 'en',
            'timezone' => 'UTC',
            'hourly_rate' => 0.00,
            'email_verified_at' => now(),
        ]);
    }
}