<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $hospitalAdminRole = Role::where('slug', 'hospital-admin')->first();
        $radiologistRole = Role::where('slug', 'radiologist')->first();
        $consultantRole = Role::where('slug', 'consultant')->first();
        
        // Get hospital
        $hospital = Hospital::first();
        
        if (!$hospital) {
            throw new \Exception('No hospital found. Please run the HospitalSeeder first.');
        }

        // Add Dr. Tai user with Hospital Admin role
        $drTai = User::updateOrCreate(
            ['email' => 'drtai@qmed.asia'],
            [
                'name' => 'Dr. Tai',
                'password' => Hash::make('88888888'),
                'email_verified_at' => now(),
                'hospital_id' => $hospital->id,
            ]
        );
        
        // Make sure Dr. Tai has the Hospital Admin role
        if (!$drTai->hasRole('hospital-admin')) {
            $drTai->roles()->sync([$hospitalAdminRole->id]);
        }
        
        // Add Dr. Eric user with Radiologist role
        $drEric = User::updateOrCreate(
            ['email' => 'dreric@qmed.asia'],
            [
                'name' => 'Dr. Eric',
                'password' => Hash::make('88888888'),
                'email_verified_at' => now(),
                'hospital_id' => $hospital->id,
            ]
        );
        
        // Make sure Dr. Eric has the Radiologist role
        if (!$drEric->hasRole('radiologist')) {
            $drEric->roles()->sync([$radiologistRole->id]);
        }
        
        // Add test user if needed, no specific role
        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'hospital_id' => $hospital->id,
            ]
        );
    }
}
