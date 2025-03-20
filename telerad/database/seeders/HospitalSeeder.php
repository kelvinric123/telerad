<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add QMed hospital if it doesn't exist
        if (!Hospital::where('name', 'QMed Hospital')->exists()) {
            Hospital::create([
                'name' => 'QMed Hospital',
                'description' => 'A leading healthcare provider specializing in medical imaging and diagnostics.',
                'address' => '123 Medical Street',
                'city' => 'Singapore',
                'state' => 'Singapore',
                'postal_code' => '123456',
                'country' => 'Singapore',
                'phone' => '+65 1234 5678',
                'fax' => '+65 1234 5679',
                'email' => 'info@qmed.asia',
                'website' => 'https://qmed.asia',
                'tax_id' => 'SG12345678',
                'registration_number' => 'REG987654321',
            ]);
        }
    }
} 