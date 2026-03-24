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
            // Household tracking fields for census and LGU household management
            $table->string('household_number')->nullable()->after('household_relationship');
            $table->integer('household_member_number')->nullable()->after('household_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['household_number', 'household_member_number']);
        });
    }
};
