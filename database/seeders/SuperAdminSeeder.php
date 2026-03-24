<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a super admin account with highest privileges
     */
    public function run(): void
    {
        // Check if superadmin already exists
        $existingSuperAdmin = Resident::where('email', 'superadmin@buguey.gov.ph')->first();
        
        if ($existingSuperAdmin) {
            $this->command->warn('Super Admin account already exists!');
            $this->command->info('Email: superadmin@buguey.gov.ph');
            return;
        }

        // Create super admin user
        $superAdmin = Resident::create([
            'first_name' => 'Super',
            'middle_name' => null,
            'last_name' => 'Administrator',
            'extension_name' => null,
            'date_of_birth' => '1985-01-01',
            'place_of_birth' => 'Buguey, Cagayan',
            'gender' => 'Other',
            'civil_status' => 'Single',
            'blood_type' => 'O+',
            'purok' => 'Admin Office',
            'barangay' => 'Buguey',
            'municipality' => 'Buguey',
            'province' => 'Cagayan',
            'contact_number' => '09123456700',
            'email' => 'superadmin@buguey.gov.ph',
            'email_verified_at' => now(),
            'password' => 'SuperAdmin@2026', // Model auto-hashes via 'hashed' cast
            'occupation' => 'System Administrator',
            'vulnerable_sector' => 'None',
            'is_verified' => true,
            'role' => 'SA',
            'profile_matched' => true,
            'profile_matched_at' => now(),
            'verification_method' => 'manual',
        ]);

        $this->command->info('╔════════════════════════════════════════════════╗');
        $this->command->info('║   SUPER ADMIN ACCOUNT CREATED SUCCESSFULLY!    ║');
        $this->command->info('╚════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info('Email: superadmin@buguey.gov.ph');
        $this->command->warn('Password: SuperAdmin@2026');
        $this->command->info('');
        $this->command->warn('⚠️  SECURITY WARNING:');
        $this->command->warn('   Change this password immediately after first login!');
        $this->command->info('');
        $this->command->info('Super Admin Capabilities:');
        $this->command->info('  ✓ Full system access');
        $this->command->info('  ✓ Data collection dashboard (HN→HHN→HHM hierarchy)');
        $this->command->info('  ✓ Resident verification and management');
        $this->command->info('  ✓ Admin user creation and role management');
        $this->command->info('  ✓ System configuration and security settings');
        $this->command->info('  ✓ Complete audit log access');
        $this->command->info('');
    }
}
