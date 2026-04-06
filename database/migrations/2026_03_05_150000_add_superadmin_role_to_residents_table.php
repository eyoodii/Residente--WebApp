<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds 'SA' (Super Admin) role to the residents table enum
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SA', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE residents DROP CONSTRAINT IF EXISTS residents_role_check");
            DB::statement("ALTER TABLE residents ADD CONSTRAINT residents_role_check CHECK (role IN ('SA', 'admin', 'citizen', 'visitor'))");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET NOT NULL");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET DEFAULT 'visitor'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE residents DROP CONSTRAINT IF EXISTS residents_role_check");
            DB::statement("ALTER TABLE residents ADD CONSTRAINT residents_role_check CHECK (role IN ('admin', 'citizen', 'visitor'))");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET NOT NULL");
            DB::statement("ALTER TABLE residents ALTER COLUMN role SET DEFAULT 'visitor'");
        }
    }
};
