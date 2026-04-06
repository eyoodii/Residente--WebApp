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

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SA', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE residents DROP CONSTRAINT IF EXISTS residents_role_check");
            DB::statement("ALTER TABLE residents ADD CONSTRAINT residents_role_check CHECK (role IN ('SA', 'admin', 'citizen', 'visitor'))");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET NOT NULL");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET DEFAULT 'visitor'");
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

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SP', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE residents DROP CONSTRAINT IF EXISTS residents_role_check");
            DB::statement("ALTER TABLE residents ADD CONSTRAINT residents_role_check CHECK (role IN ('SP', 'admin', 'citizen', 'visitor'))");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET NOT NULL");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET DEFAULT 'visitor'");
        }

        DB::table('residents')
            ->where('email', 'superadmin@buguey.gov.ph')
            ->update(['role' => 'SP']);
    }
};
