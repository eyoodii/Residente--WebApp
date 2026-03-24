<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates service_requests status enum to include 'ready-for-pickup'
     */
    public function up(): void
    {
        // Note: In MySQL, you cannot directly modify ENUM. 
        // This is a workaround that drops and recreates the column
        Schema::table('service_requests', function (Blueprint $table) {
            // First, add a temporary column
            $table->string('status_temp')->default('pending')->after('service_id');
        });

        // Copy existing data to temp column
        DB::statement('UPDATE service_requests SET status_temp = status');

        Schema::table('service_requests', function (Blueprint $table) {
            // Drop the old status column
            $table->dropColumn('status');
        });

        Schema::table('service_requests', function (Blueprint $table) {
            // Recreate status with updated enum values
            $table->enum('status', [
                'pending',
                'in-progress',
                'ready-for-pickup',
                'completed',
                'cancelled',
                'rejected'
            ])->default('pending')->after('service_id');
        });

        // Copy data back from temp to new status column
        DB::statement('UPDATE service_requests SET status = status_temp');

        Schema::table('service_requests', function (Blueprint $table) {
            // Drop the temporary column
            $table->dropColumn('status_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original status enum
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('status_temp')->default('pending')->after('service_id');
        });

        DB::statement('UPDATE service_requests SET status_temp = status');

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('service_id');
        });

        DB::statement('UPDATE service_requests SET status = status_temp');

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }
};
