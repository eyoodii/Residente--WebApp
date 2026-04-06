<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        // Switch from ENUM to VARCHAR so we can support new values like "Co-Head" without schema churn
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE household_members MODIFY relationship VARCHAR(100) NOT NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE household_members DROP CONSTRAINT IF EXISTS household_members_relationship_check");
            DB::statement("ALTER TABLE household_members ALTER COLUMN relationship TYPE VARCHAR(100) USING relationship::text");
            DB::statement("ALTER TABLE household_members ALTER COLUMN relationship SET NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        // Revert to the original ENUM set (without Co-Head). This may fail if records contain other values.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE household_members MODIFY relationship ENUM('Spouse','Son','Daughter','Father','Mother','Brother','Sister','Grandchild','Other Relative') NOT NULL");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("UPDATE household_members SET relationship = 'Other Relative' WHERE relationship NOT IN ('Spouse','Son','Daughter','Father','Mother','Brother','Sister','Grandchild','Other Relative')");
            DB::statement("ALTER TABLE household_members DROP CONSTRAINT IF EXISTS household_members_relationship_check");
            DB::statement("ALTER TABLE household_members ALTER COLUMN relationship TYPE VARCHAR(255) USING relationship::text");
            DB::statement("ALTER TABLE household_members ADD CONSTRAINT household_members_relationship_check CHECK (relationship IN ('Spouse','Son','Daughter','Father','Mother','Brother','Sister','Grandchild','Other Relative'))");
            DB::statement("ALTER TABLE household_members ALTER COLUMN relationship SET NOT NULL");
        }
    }
};
