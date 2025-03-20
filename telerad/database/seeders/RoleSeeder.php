<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Hospital Admin',
                'slug' => 'hospital-admin',
                'description' => 'Administrator for a hospital who can add radiologists and consultants',
            ],
            [
                'name' => 'Radiologist',
                'slug' => 'radiologist',
                'description' => 'Radiologist who can view studies and create reports',
            ],
            [
                'name' => 'Consultant',
                'slug' => 'consultant',
                'description' => 'Consultant who can view reports and make recommendations',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
} 