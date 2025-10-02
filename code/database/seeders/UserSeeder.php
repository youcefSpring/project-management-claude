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
            'email_verified_at' => now(),
        ]);

        // Create project manager
        User::create([
            'name' => 'Project Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'language' => 'en',
            'email_verified_at' => now(),
        ]);

        // Create team members
        $teamMembers = [
            [
                'name' => 'John Developer',
                'email' => 'john@example.com',
                'role' => 'member',
            ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@example.com',
                'role' => 'member',
            ],
            [
                'name' => 'Mike Tester',
                'email' => 'mike@example.com',
                'role' => 'member',
            ],
            [
                'name' => 'Sarah Frontend',
                'email' => 'sarah@example.com',
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
            ]);
        }

        // Create another member user
        User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'language' => 'en',
            'email_verified_at' => now(),
        ]);
    }
}