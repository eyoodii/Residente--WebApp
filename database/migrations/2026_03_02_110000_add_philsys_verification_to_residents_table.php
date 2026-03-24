<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds PhilSys verification tracking for E-Services access control
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // PhilSys Verification - Required for requesting official documents
            $table->timestamp('philsys_verified_at')->nullable()->after('email_verified_at');
            $table->string('philsys_verification_method')->nullable()->after('philsys_verified_at'); // 'qr_scan', 'manual_verify', 'biometric'
            $table->string('philsys_transaction_id')->nullable()->after('philsys_verification_method'); // Reference ID from PhilSys API
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'philsys_verified_at',
                'philsys_verification_method',
                'philsys_transaction_id'
            ]);
        });
    }
};
