<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds DB::statement import for migration that need it
     */
    public function up(): void
    {
        // This migration exists only to ensure DB facade is available
        // No actual schema changes needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
