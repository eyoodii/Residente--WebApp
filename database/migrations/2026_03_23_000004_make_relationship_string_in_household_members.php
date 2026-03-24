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
        // Switch from ENUM to VARCHAR so we can support new values like "Co-Head" without schema churn
        DB::statement("ALTER TABLE household_members MODIFY relationship VARCHAR(100) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original ENUM set (without Co-Head). This may fail if records contain other values.
        DB::statement("ALTER TABLE household_members MODIFY relationship ENUM('Spouse','Son','Daughter','Father','Mother','Brother','Sister','Grandchild','Other Relative') NOT NULL");
    }
};
