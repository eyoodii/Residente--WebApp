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
        Schema::table('household_members', function (Blueprint $table) {
            // Active OFW Fields
            $table->boolean('is_active_ofw')->default(false)->after('is_4ps_beneficiary');
            $table->string('ofw_country')->nullable()->after('is_active_ofw');
            $table->string('ofw_nature_of_work')->nullable()->after('ofw_country');
            $table->year('ofw_year_deployed')->nullable()->after('ofw_nature_of_work');
            
            // Returned OFW Fields
            $table->boolean('is_returned_ofw')->default(false)->after('ofw_year_deployed');
            $table->year('ofw_year_returned')->nullable()->after('is_returned_ofw');
            $table->enum('ofw_nature_of_return', ['Permanent', 'Temporary', 'Vacation'])->nullable()->after('ofw_year_returned');
            
            // Local Migrant Worker
            $table->boolean('is_local_migrant')->default(false)->after('ofw_nature_of_return');
            $table->string('local_migrant_location')->nullable()->after('is_local_migrant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('household_members', function (Blueprint $table) {
            $table->dropColumn([
                'is_active_ofw',
                'ofw_country',
                'ofw_nature_of_work',
                'ofw_year_deployed',
                'is_returned_ofw',
                'ofw_year_returned',
                'ofw_nature_of_return',
                'is_local_migrant',
                'local_migrant_location',
            ]);
        });
    }
};
