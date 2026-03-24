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
            // Physical address fields collected at registration
            $table->string('house_number', 100)->nullable()->after('purok');
            $table->string('street', 150)->nullable()->after('house_number');
            // Indicates whether the resident is registering as a new family or joining an existing one
            $table->string('family_registration_type', 30)->nullable()->after('street');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['house_number', 'street', 'family_registration_type']);
        });
    }
};
