<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the primary household (physical address) table
     * HN (Household Number) - The Physical House tied to Geographic Address
     */
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            
            // HN - Household Number (unique identifier for physical address)
            // Format: HN-YYYY-NNNN (e.g., HN-2026-0001)
            $table->string('household_number')->unique();
            
            // Geographic Address (defines the physical location)
            $table->string('house_number')->nullable(); // Physical house/lot number
            $table->string('street')->nullable();
            $table->string('purok');
            $table->string('barangay');
            $table->string('municipality')->default('Buguey');
            $table->string('province')->default('Cagayan');
            
            // Full address string for display and search
            $table->string('full_address')->nullable();
            
            // GPS coordinates for mapping (optional)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Housing Information (moved from household_profiles)
            $table->enum('housing_type', [
                'Owned',
                'Rented',
                'Rent-Free with Consent',
                'Informal Settler'
            ])->default('Owned');
            
            // Status tracking
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for quick address lookups
            $table->index(['barangay', 'purok']);
            $table->index('full_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
