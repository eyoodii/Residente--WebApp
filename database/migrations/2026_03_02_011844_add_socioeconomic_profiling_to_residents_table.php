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
        Schema::table('residents', function (Blueprint $table) {
            // Housing & Sanitation
            $table->string('residential_type')->nullable();
            $table->string('house_materials')->nullable();
            $table->string('water_source')->nullable();
            $table->boolean('flood_prone')->default(false);
            $table->boolean('sanitary_toilet')->default(false);
            
            // Livelihood - Stored as JSON arrays
            $table->json('crops')->nullable();
            $table->json('aquaculture')->nullable();
            $table->json('livestock')->nullable();
            $table->json('fisheries')->nullable();
            
            // Onboarding Status
            $table->boolean('is_onboarding_complete')->default(false);
            $table->timestamp('onboarding_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'residential_type',
                'house_materials',
                'water_source',
                'flood_prone',
                'sanitary_toilet',
                'crops',
                'aquaculture',
                'livestock',
                'fisheries',
                'is_onboarding_complete',
                'onboarding_completed_at',
            ]);
        });
    }
};
