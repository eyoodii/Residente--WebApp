<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Buguey Barangays Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration contains all 30 barangays of Buguey, Cagayan
    | with their standardized 3-letter abbreviations/codes and official
    | PSGC (Philippine Standard Geographic Code) codes for Philsys integration.
    |
    | Format: 'Full Name' => ['code' => 'CODE', 'psgc' => 'PSGC_CODE']
    | These codes are used for database IDs, dropdowns, and record tracking
    | (e.g., BGY-ALW-001, BGY-CEN-042)
    |
    | PSGC Data Source: Philippine Statistics Authority (PSA)
    | https://psa.gov.ph/classification/psgc
    |
    */

    /*
    |--------------------------------------------------------------------------
    | PSGC Location Codes for Buguey, Cagayan
    |--------------------------------------------------------------------------
    */
    'psgc' => [
        'region' => [
            'code' => '200000000',
            'name' => 'Region II (Cagayan Valley)',
        ],
        'province' => [
            'code' => '201500000',
            'name' => 'Cagayan',
        ],
        'city' => [
            'code' => '201508000',
            'name' => 'Buguey',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Barangay List with Codes and PSGC
    |--------------------------------------------------------------------------
    */
    'list' => [
        'Alucao Weste' => 'ALW',
        'Antiporda' => 'ANT',
        'Ballang' => 'BAL',
        'Balza' => 'BLZ',
        'Cabaritan' => 'CAB',
        'Calamegatanan' => 'CAL',
        'Centro' => 'CEN',
        'Centro West' => 'CEW',
        'Dalaya' => 'DAL',
        'Fula' => 'FUL',
        'Leron' => 'LER',
        'Maddalero' => 'MAD',
        'Mala Este' => 'MAE',
        'Mala Weste' => 'MAW',
        'Minanga Este' => 'MIE',
        'Minanga Weste' => 'MIW',
        'Paddaya Este' => 'PAE',
        'Paddaya Weste' => 'PAW',
        'Pattao' => 'PAT',
        'Quinawegan' => 'QUI',
        'Remebella' => 'REM',
        'San Isidro' => 'SAI',
        'San Juan' => 'SAJ',
        'San Vicente' => 'SAV',
        'Santa Isabel' => 'STI',
        'Santa Maria' => 'STM',
        'Tabbac' => 'TAB',
        'Villa Cielo' => 'VIC',
        'Villa Gracia' => 'VIG',
        'Villa Leonora' => 'VIL',
    ],

    /*
    |--------------------------------------------------------------------------
    | PSGC Codes for Each Barangay
    |--------------------------------------------------------------------------
    | Official 9/10-digit PSGC codes from the Philippine Statistics Authority.
    | Used for Philsys integration and address verification.
    |
    */
    'psgc_codes' => [
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    // Get barangay code by name
    'getCode' => function ($name) {
        return config('barangays.list')[$name] ?? null;
    },

    // Get barangay name by code
    'getName' => function ($code) {
        $list = config('barangays.list');
        $flipped = array_flip($list);
        return $flipped[$code] ?? null;
    },

    // Get all barangay names
    'names' => function () {
        return array_keys(config('barangays.list'));
    },

    // Get all barangay codes
    'codes' => function () {
        return array_values(config('barangays.list'));
    },

    // Format for dropdown display: "Barangay Name (CODE)"
    'formatted' => function () {
        $list = config('barangays.list');
        $formatted = [];
        foreach ($list as $name => $code) {
            $formatted[$name] = $name . ' (' . $code . ')';
        }
        return $formatted;
    },

    /*
    |--------------------------------------------------------------------------
    | PSGC Helper Methods
    |--------------------------------------------------------------------------
    */

    // Get PSGC code for a barangay by name
    'getPsgcCode' => function ($name) {
        return config('barangays.psgc_codes')[$name] ?? null;
    },

    // Get barangay name by PSGC code
    'getNameByPsgc' => function ($psgcCode) {
        $codes = config('barangays.psgc_codes');
        $flipped = array_flip($codes);
        return $flipped[$psgcCode] ?? null;
    },

    // Get all PSGC codes as flat array
    'allPsgcCodes' => function () {
        return array_values(array_unique(config('barangays.psgc_codes')));
    },

    // Get the municipality PSGC code for Buguey
    'getCityPsgcCode' => function () {
        return config('barangays.psgc.city.code');
    },

    // Get the province PSGC code for Cagayan
    'getProvincePsgcCode' => function () {
        return config('barangays.psgc.province.code');
    },

    // Get the region PSGC code for Region II
    'getRegionPsgcCode' => function () {
        return config('barangays.psgc.region.code');
    },

    // Get complete PSGC location info for a resident
    'getCompletePsgcInfo' => function ($barangayName = null) {
        $info = [
            'region' => config('barangays.psgc.region'),
            'province' => config('barangays.psgc.province'),
            'city' => config('barangays.psgc.city'),
        ];
        
        if ($barangayName) {
            $psgcCode = config('barangays.psgc_codes')[$barangayName] ?? null;
            $info['barangay'] = [
                'code' => $psgcCode,
                'name' => $barangayName,
            ];
        }
        
        return $info;
    },
];
