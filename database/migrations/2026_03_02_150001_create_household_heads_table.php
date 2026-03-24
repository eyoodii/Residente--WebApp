<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the household heads (family units) table
     * HHN (Household Head Number) - The Family Unit tied to HN
     * Multiple HHNs can exist in one HN (e.g., two families in one house)
     */
    public function up(): void
    {
        Schema::create('household_heads', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to household (physical address)
            $table->foreignId('household_id')->constrained('households')->onDelete('cascade');
            
            // Foreign key to resident (the head of this family unit)
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            
            // HHN - Household Head Number (unique within the household)
            // Format: HHN-NN (e.g., HHN-01, HHN-02 for families in same house)
            $table->string('household_head_number');
            
            // Primary surname for this family unit (used for automatic member recognition)
            $table->string('surname');
            
            // Family information
            $table->integer('family_size')->default(1); // Auto-calculated
            $table->string('family_name')->nullable(); // Optional family identifier
            
            // Status
            $table->boolean('is_primary_family')->default(false); // First family at this address
            $table->boolean('is_active')->default(true);
            
            // Aid/Assistance tracking
            $table->boolean('is_4ps_beneficiary')->default(false);
            $table->text('assistance_programs')->nullable(); // JSON of programs
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('surname');
            $table->index(['household_id', 'surname']);
            $table->unique(['household_id', 'household_head_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_heads');
    }
};
