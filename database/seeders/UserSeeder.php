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
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'super@admin.com',
            'password' => Hash::make('SuperAdmin123!'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super-admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Admin123!'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Moderator
        $moderator = User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
            'password' => Hash::make('Moderator123!'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $moderator->assignRole('moderator');

        // Create Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('User123!'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $user->assignRole('user');

        // Create additional test users
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
