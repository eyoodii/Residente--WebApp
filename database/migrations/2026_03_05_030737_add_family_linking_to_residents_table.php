<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds family linkage and auto-linking validation flag to residents
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Link to family unit (HHN) - nullable until assigned
            $table->foreignId('family_id')->nullable()->after('household_id')->constrained()->nullOnDelete();
            
            // Auto-linking validation flag for admin review
            // TRUE = system auto-linked based on surname + address match (needs review)
            // FALSE = manually verified or confirmed by admin
            $table->boolean('is_auto_linked')->default(false)->after('family_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            $table->dropColumn(['family_id', 'is_auto_linked']);
        });
    }
};
