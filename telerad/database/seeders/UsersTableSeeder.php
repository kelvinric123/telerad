<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add test user if it doesn't exist
        if (!User::where('email', 'test@example.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Add Dr. Tai user if it doesn't exist
        if (!User::where('email', 'drtai@qmed.asia')->exists()) {
            User::create([
                'name' => 'Dr. Tai',
                'email' => 'drtai@qmed.asia',
                'password' => Hash::make('88888888'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
