<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@costmodel.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@costmodel.com',
                'password' => Hash::make('password123'),
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'user@costmodel.com'],
            [
                'name' => 'Test User',
                'email' => 'user@costmodel.com',
                'password' => Hash::make('password123'),
            ]
        );
    }
} 