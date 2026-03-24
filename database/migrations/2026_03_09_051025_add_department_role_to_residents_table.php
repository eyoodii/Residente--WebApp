<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds department_role column to support LGU staff RBAC.
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Short code representing the LGU department (e.g. 'MAYOR', 'TRESR', 'HRMO')
            $table->string('department_role', 10)->nullable()->after('role');

            // Optional JSON override for custom per-user permissions within their department
            $table->json('department_permissions')->nullable()->after('department_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['department_role', 'department_permissions']);
        });
    }
};
