<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the philsys_verifications table to store verification records
     * including card image references and verification metadata.
     */
    public function up(): void
    {
        Schema::create('philsys_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            
            // Verification transaction info
            $table->string('transaction_id', 50)->unique();
            $table->string('verification_method', 20); // qr_scan, manual_verify, admin_manual
            $table->string('result_code', 30); // SUCCESS, INVALID_FORMAT, etc.
            
            // National ID (encrypted in application layer)
            $table->string('national_id_hash', 64)->nullable(); // SHA-256 hash for duplicate detection
            
            // Card image paths (stored in private disk)
            $table->string('card_front_path')->nullable();
            $table->string('card_back_path')->nullable();
            
            // PSGC codes at time of verification
            $table->string('region_psgc_code', 10)->nullable();
            $table->string('province_psgc_code', 10)->nullable();
            $table->string('city_psgc_code', 10)->nullable();
            $table->string('barangay_psgc_code', 10)->nullable();
            
            // Verification metadata
            $table->json('qr_data')->nullable(); // Parsed QR code data
            $table->json('match_results')->nullable(); // Results of data matching
            $table->json('address_validation')->nullable(); // PSGC validation results
            
            // Admin verification fields
            $table->foreignId('verified_by_admin_id')->nullable()->constrained('residents')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            
            // Audit fields
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_successful')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('national_id_hash');
            $table->index('is_successful');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('philsys_verifications');
    }
};
