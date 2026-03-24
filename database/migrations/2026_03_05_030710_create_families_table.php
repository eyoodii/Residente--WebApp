<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the families table - HHN (Household Head Number) level
     * Represents a family unit within a physical household
     * Multiple families can exist in one physical house
     */
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            
            // Link to the physical house (HN)
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            
            // HHN - Household Head Number (unique identifier for family unit)
            // Format: HHN-YYYY-NNNN (e.g., HHN-2026-0001)
            $table->string('hhn_number')->unique();
            
            // Family Identification
            $table->string('head_surname'); // Used for automatic surname recognition logic
            $table->foreignId('household_head_id')->nullable()->constrained('residents')->nullOnDelete();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for quick lookups
            $table->index(['household_id', 'head_surname']);
            $table->index('hhn_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
