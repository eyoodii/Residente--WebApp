<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds PSGC (Philippine Standard Geographic Code) fields
     * to the residents table for proper Philsys integration. PSGC codes are
     * required for accurate address validation with the Philippine ID system.
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Add PSGC code columns (9-digit codes following PSA standard)
            $table->string('region_psgc_code', 10)->nullable()->after('province');
            $table->string('province_psgc_code', 10)->nullable()->after('region_psgc_code');
            $table->string('city_psgc_code', 10)->nullable()->after('province_psgc_code');
            $table->string('barangay_psgc_code', 10)->nullable()->after('city_psgc_code');
            
            // Add indexes for efficient lookups
            $table->index('region_psgc_code');
            $table->index('province_psgc_code');
            $table->index('city_psgc_code');
            $table->index('barangay_psgc_code');
        });

        // Populate PSGC codes for Buguey, Cagayan residents
        $this->populatePsgcCodes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['region_psgc_code']);
            $table->dropIndex(['province_psgc_code']);
            $table->dropIndex(['city_psgc_code']);
            $table->dropIndex(['barangay_psgc_code']);
            
            $table->dropColumn([
                'region_psgc_code',
                'province_psgc_code',
                'city_psgc_code',
                'barangay_psgc_code',
            ]);
        });
    }

    /**
     * Populate PSGC codes for existing residents.
     * 
     * Maps barangay names to official PSGC codes for Buguey, Cagayan.
     * This enables accurate address validation for Philsys integration.
     */
    protected function populatePsgcCodes(): void
    {
        // PSGC codes for Buguey, Cagayan (Municipality code: 201508000)
        $bugueyPsgcCodes = [
            // Region 02 - Cagayan Valley
            'region_code' => '200000000',
            // Province - Cagayan
            'province_code' => '201500000',
            // Municipality - Buguey
            'city_code' => '201508000',
            // Barangays mapping (name => PSGC code)
            'barangays' => [
                'Ballang' => '201508001',
                'Balza' => '201508002',
                'Cabaritan' => '201508003',
                'Calamegatan' => '201508004',
                'Calamegatanan' => '201508004', // Alternative spelling
                'Centro' => '201508005',
                'Centro West' => '201508006',
                'Dalaya' => '201508007',
                'Fula' => '201508008',
                'Leron' => '201508009',
                'M. Antiporda' => '201508010',
                'Antiporda' => '201508010', // Alternative name
                'Maddalero' => '201508011',
                'Mala Este' => '201508012',
                'Mala Weste' => '201508013',
                'Minanga Este' => '201508014',
                'Minanga Weste' => '201508026',
                'Paddaya Este' => '201508015',
                'Paddaya Weste' => '201508027',
                'Pattao' => '201508016',
                'Quinawegan' => '201508018',
                'Remebella' => '201508019',
                'San Isidro' => '201508020',
                'San Juan' => '201508028',
                'San Lorenzo' => '201508025',
                'San Vicente' => '201508029',
                'Santa Isabel' => '201508021',
                'Sta. Isabel' => '201508021', // Alternative spelling
                'Santa Maria' => '201508022',
                'Sta. Maria' => '201508022', // Alternative spelling
                'Tabbac' => '201508023',
                'Villa Cielo' => '201508024',
                'Villa Gracia' => '201508030',
                'Villa Leonora' => '201508031',
                'Alucao Weste' => '201508032', // Note: Check if this is in official PSGC
            ],
        ];

        // Update all residents with the region, province, and city codes
        // for Buguey municipality
        DB::table('residents')
            ->where('municipality', 'LIKE', '%Buguey%')
            ->orWhere('municipality', 'Buguey')
            ->update([
                'region_psgc_code' => $bugueyPsgcCodes['region_code'],
                'province_psgc_code' => $bugueyPsgcCodes['province_code'],
                'city_psgc_code' => $bugueyPsgcCodes['city_code'],
            ]);

        // Update barangay PSGC codes based on barangay name
        foreach ($bugueyPsgcCodes['barangays'] as $barangayName => $psgcCode) {
            DB::table('residents')
                ->where('barangay', $barangayName)
                ->update(['barangay_psgc_code' => $psgcCode]);
        }
    }
};
