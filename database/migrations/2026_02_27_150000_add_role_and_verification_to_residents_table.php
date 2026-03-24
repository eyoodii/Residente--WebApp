<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds role-based access control and enhanced security fields
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Role-Based Access Control (RBAC)
            $table->enum('role', ['admin', 'citizen', 'visitor'])->default('visitor')->after('is_verified');
            
            // Profile Matching & Verification
            $table->boolean('profile_matched')->default(false)->after('role');
            $table->timestamp('profile_matched_at')->nullable()->after('profile_matched');
            $table->string('verification_method')->nullable()->after('profile_matched_at'); // 'manual', 'auto', 'biometric'
            
            // Security & Audit
            $table->timestamp('last_login_at')->nullable()->after('verification_method');
            $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            $table->integer('failed_login_attempts')->default(0)->after('last_login_ip');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'profile_matched',
                'profile_matched_at',
                'verification_method',
                'last_login_at',
                'last_login_ip',
                'failed_login_attempts',
                'locked_until'
            ]);
        });
    }
};
