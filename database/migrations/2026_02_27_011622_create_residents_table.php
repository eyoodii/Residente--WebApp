<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            
            // Core Identity
            $table->string('national_id')->unique()->nullable(); // PhilSys ID integration
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable(); // Jr., Sr., III
            
            // Demographics & Analytics
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Legally Separated']);
            $table->string('blood_type')->nullable();
            
            // LGU Specific Location
            $table->string('purok');
            $table->string('barangay');
            $table->string('municipality')->default('Buguey');
            $table->string('province')->default('Cagayan');
            
            // Contact & Socio-Economic Data
            $table->string('contact_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('occupation')->nullable();
            $table->enum('vulnerable_sector', [
                'None', 
                'Senior Citizen', 
                'PWD', 
                'Solo Parent', 
                'Indigenous People'
            ])->default('None');
            
            // System Tracking
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes(); // Keeps records intact for historical data even if removed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
