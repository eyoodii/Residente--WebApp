<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Links residents to their household head (family unit)
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Link to household (physical address)
            $table->foreignId('household_id')
                ->nullable()
                ->after('household_member_number')
                ->constrained('households')
                ->onDelete('set null');
            
            // Link to household head (family unit)
            $table->foreignId('household_head_id')
                ->nullable()
                ->after('household_id')
                ->constrained('household_heads')
                ->onDelete('set null');
            
            // Is this resident a household head?
            $table->boolean('is_household_head')->default(false)->after('household_head_id');
            
            $table->index(['household_id', 'last_name']);
            $table->index('household_head_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['household_id']);
            $table->dropForeign(['household_head_id']);
            $table->dropColumn([
                'household_id',
                'household_head_id', 
                'is_household_head'
            ]);
        });
    }
};
