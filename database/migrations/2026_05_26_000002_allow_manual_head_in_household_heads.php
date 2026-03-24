<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow household heads to be registered without a linked resident account.
     * Adds manual name fields and makes resident_id nullable.
     */
    public function up(): void
    {
        Schema::table('household_heads', function (Blueprint $table) {
            // Make resident_id optional so a head can be entered manually
            $table->foreignId('resident_id')->nullable()->change();

            // Manual entry fields (used when no resident account exists)
            $table->string('head_first_name', 100)->nullable()->after('resident_id');
            $table->string('head_last_name', 100)->nullable()->after('head_first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('household_heads', function (Blueprint $table) {
            $table->dropColumn(['head_first_name', 'head_last_name']);
            $table->foreignId('resident_id')->nullable(false)->change();
        });
    }
};
