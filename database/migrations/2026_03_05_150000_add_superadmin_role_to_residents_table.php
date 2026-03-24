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
        // For MySQL, we need to modify the enum by recreating it
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('SA', 'admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove SP role, revert to original enum
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE residents MODIFY COLUMN role ENUM('admin', 'citizen', 'visitor') NOT NULL DEFAULT 'visitor'");
        }
    }
};
