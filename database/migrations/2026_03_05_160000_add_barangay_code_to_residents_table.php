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
            $table->string('barangay_code', 3)->nullable()->after('barangay')->index();
        });

        // Populate barangay_code for existing residents based on barangay name
        $this->populateBarangayCodes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('barangay_code');
        });
    }

    /**
     * Populate barangay codes for existing residents
     */
    protected function populateBarangayCodes(): void
    {
        $barangayMapping = config('barangays.list', []);
        
        foreach ($barangayMapping as $name => $code) {
            DB::table('residents')
                ->where('barangay', $name)
                ->update(['barangay_code' => $code]);
        }

        // Handle old barangay names that may exist in database
        $legacyMappings = [
            'M. Antiporda' => 'ANT',
            'Calamegatan' => 'CAL',
            'Sta. Isabel' => 'STI',
            'Sta. Maria' => 'STM',
        ];

        foreach ($legacyMappings as $oldName => $code) {
            DB::table('residents')
                ->where('barangay', $oldName)
                ->update([
                    'barangay_code' => $code,
                    'barangay' => config('barangays.getName')($code) ?? $oldName
                ]);
        }
    }
};
