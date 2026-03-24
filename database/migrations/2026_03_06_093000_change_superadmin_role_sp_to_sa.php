<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Changes SuperAdmin role from 'SP' to 'SA'
     */
    public function up(): void
    {
        // First update any existing SP roles to SA (before changing enum)
        DB::table('residents')->where('role', 'SP')->update(['role' => 'visitor']);
        
        // Change the enum to use SA instead of SP if not using SQLite
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SA', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        }
        
        // Update the temporary visitor back to SA
        DB::table('residents')
            ->where('email', 'superadmin@buguey.gov.ph')
            ->update(['role' => 'SA']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('residents')->where('role', 'SA')->update(['role' => 'visitor']);
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SP', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        }
        DB::table('residents')
            ->where('email', 'superadmin@buguey.gov.ph')
            ->update(['role' => 'SP']);
    }
};
