<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PSGCSeeder::class,              // 1. Geographic reference data (regions, provinces, etc.)
            PermissionSeeder::class,        // 2. Roles & permissions
            DepartmentModuleSeeder::class,  // 3. Department module access config
            SuperAdminSeeder::class,        // 4. Super admin account
            AdminUserSeeder::class,         // 5. Admin account
            DepartmentStaffSeeder::class,   // 6. Department staff accounts
            ServiceSeederComplete::class,   // 7. Services catalog
            AnnouncementSeeder::class,      // 8. Sample announcements
            HierarchicalDataSeeder::class,  // 9. Sample households/families/residents
        ]);
    }
}
