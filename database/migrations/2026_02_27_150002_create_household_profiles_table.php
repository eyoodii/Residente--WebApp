<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores household-level socio-economic data for LGU analytics
     */
    public function up(): void
    {
        Schema::create('household_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade'); // Head of household
            
            // Housing Information
            $table->enum('housing_type', [
                'Owned',
                'Rented',
                'Rent-Free with Consent',
                'Informal Settler'
            ])->default('Owned');
            $table->enum('dwelling_type', [
                'Single Detached',
                'Duplex',
                'Apartment',
                'Townhouse',
                'Makeshift/Salvaged',
                'Others'
            ])->nullable();
            $table->integer('number_of_rooms')->nullable();
            
            // Utilities & Amenities
            $table->boolean('has_electricity')->default(true);
            $table->boolean('has_water_supply')->default(true);
            $table->enum('water_source', [
                'Community Water System',
                'Own Use Water System',
                'Deep Well',
                'Spring/River',
                'Peddler/Vendor',
                'Others'
            ])->nullable();
            $table->enum('toilet_facility', [
                'Water Sealed Sewer',
                'Water Sealed Septic Tank',
                'Closed Pit',
                'Open Pit',
                'None'
            ])->nullable();
            
            // Communication & Technology
            $table->boolean('has_internet_access')->default(false);
            $table->boolean('has_television')->default(false);
            $table->boolean('has_radio')->default(false);
            
            // Income & Livelihood
            $table->decimal('total_household_income', 12, 2)->nullable();
            $table->enum('income_classification', [
                'Below Poverty Threshold',
                'Low Income',
                'Lower Middle Income',
                'Middle Income',
                'Upper Middle Income',
                'High Income'
            ])->nullable();
            
            // Assets
            $table->boolean('owns_vehicle')->default(false);
            $table->string('vehicle_types')->nullable(); // JSON array stored as string
            $table->boolean('owns_agricultural_land')->default(false);
            $table->decimal('agricultural_land_area', 10, 2)->nullable(); // in hectares
            
            // Additional Notes for Assistance Programs
            $table->text('special_needs')->nullable();
            $table->text('assistance_received')->nullable(); // Government programs benefited from
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique('resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_profiles');
    }
};
