<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates an initial admin user for the system
     */
    public function run(): void
    {
        // Create initial admin user
        $admin = Resident::create([
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'User',
            'extension_name' => null,
            'date_of_birth' => '1990-01-01',
            'place_of_birth' => 'Buguey, Cagayan',
            'gender' => 'Other',
            'civil_status' => 'Single',
            'blood_type' => 'O+',
            'purok' => 'Admin',
            'barangay' => 'Buguey',
            'municipality' => 'Buguey',
            'province' => 'Cagayan',
            'contact_number' => '09123456789',
            'email' => 'admin@buguey.gov.ph',
            'email_verified_at' => now(),
            'password' => 'Admin@2026', // Model auto-hashes via 'hashed' cast
            'occupation' => 'Barangay Administrator',
            'vulnerable_sector' => 'None',
            'is_verified' => true,
            'role' => 'admin',
            'profile_matched' => true,
            'profile_matched_at' => now(),
            'verification_method' => 'manual',
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@buguey.gov.ph');
        $this->command->warn('Password: Admin@2026');
        $this->command->warn('IMPORTANT: Change this password after first login!');
    }
}
