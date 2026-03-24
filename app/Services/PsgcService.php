<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Schoolees\Psgc\Models\Region;
use Schoolees\Psgc\Models\Province;
use Schoolees\Psgc\Models\City;
use Schoolees\Psgc\Models\Barangay;

/**
 * PSGC Service for Philsys Integration
 * 
 * This service provides methods for validating and retrieving
 * Philippine Standard Geographic Code (PSGC) data for use with
 * the Philippine Identification System (Philsys).
 * 
 * PSGC codes are required for accurate address validation when
 * integrating with Philsys and other government systems.
 */
class PsgcService
{
    /**
     * Default location codes for Buguey, Cagayan
     */
    protected const BUGUEY_REGION_CODE = '200000000';
    protected const BUGUEY_PROVINCE_CODE = '201500000';
    protected const BUGUEY_CITY_CODE = '201508000';

    /**
     * Get complete PSGC address codes for a barangay name.
     * 
     * @param string $barangayName
     * @return array|null
     */
    public function getAddressCodes(string $barangayName): ?array
    {
        // First try from config (for Buguey barangays)
        $psgcCode = config('barangays.psgc_codes')[$barangayName] ?? null;
        
        if ($psgcCode) {
            return [
                'region_code' => self::BUGUEY_REGION_CODE,
                'province_code' => self::BUGUEY_PROVINCE_CODE,
                'city_code' => self::BUGUEY_CITY_CODE,
                'barangay_code' => $psgcCode,
            ];
        }

        // Fall back to PSGC database lookup
        $barangay = Barangay::where('name', 'LIKE', "%{$barangayName}%")->first();
        
        if (!$barangay) {
            return null;
        }

        $city = City::where('code', $barangay->city_code)->first();
        
        return [
            'region_code' => $city->region_code ?? null,
            'province_code' => $city->province_code ?? null,
            'city_code' => $barangay->city_code,
            'barangay_code' => $barangay->code,
        ];
    }

    /**
     * Get full address information from PSGC codes.
     * 
     * @param string $regionCode
     * @param string $provinceCode
     * @param string $cityCode
     * @param string $barangayCode
     * @return array
     */
    public function getAddressFromCodes(
        string $regionCode,
        string $provinceCode,
        string $cityCode,
        string $barangayCode
    ): array {
        return [
            'region' => Region::find($regionCode)?->name,
            'province' => Province::find($provinceCode)?->name,
            'city' => City::find($cityCode)?->name,
            'barangay' => Barangay::find($barangayCode)?->name,
        ];
    }

    /**
     * Validate that all PSGC codes are valid and consistent.
     * 
     * @param string $regionCode
     * @param string $provinceCode
     * @param string $cityCode
     * @param string $barangayCode
     * @return array Validation result with 'valid' boolean and 'errors' array
     */
    public function validateAddressCodes(
        string $regionCode,
        string $provinceCode,
        string $cityCode,
        string $barangayCode
    ): array {
        $errors = [];

        // Validate region exists
        $region = Region::find($regionCode);
        if (!$region) {
            $errors[] = "Invalid region code: {$regionCode}";
        }

        // Validate province exists and belongs to region
        $province = Province::find($provinceCode);
        if (!$province) {
            $errors[] = "Invalid province code: {$provinceCode}";
        } elseif ($province->region_code !== $regionCode) {
            $errors[] = "Province does not belong to the specified region";
        }

        // Validate city exists and belongs to province
        $city = City::find($cityCode);
        if (!$city) {
            $errors[] = "Invalid city/municipality code: {$cityCode}";
        } elseif ($city->province_code && $city->province_code !== $provinceCode) {
            $errors[] = "City does not belong to the specified province";
        }

        // Validate barangay exists and belongs to city
        $barangay = Barangay::find($barangayCode);
        if (!$barangay) {
            $errors[] = "Invalid barangay code: {$barangayCode}";
        } elseif ($barangay->city_code !== $cityCode) {
            $errors[] = "Barangay does not belong to the specified city";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get all barangays for a city/municipality.
     * 
     * @param string $cityCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBarangaysByCity(string $cityCode)
    {
        return Barangay::where('city_code', $cityCode)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all cities/municipalities for a province.
     * 
     * @param string $provinceCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCitiesByProvince(string $provinceCode)
    {
        return City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all provinces for a region.
     * 
     * @param string $regionCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProvincesByRegion(string $regionCode)
    {
        return Province::where('region_code', $regionCode)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all regions.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRegions()
    {
        return Region::orderBy('name')->get();
    }

    /**
     * Search barangays by name.
     * 
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchBarangays(string $query, int $limit = 10)
    {
        return Barangay::where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Get Buguey barangays from PSGC database.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBugueyBarangays()
    {
        return $this->getBarangaysByCity(self::BUGUEY_CITY_CODE);
    }

    /**
     * Format address for Philsys verification.
     * Returns a standardized address string suitable for Philsys matching.
     * 
     * @param string $regionCode
     * @param string $provinceCode
     * @param string $cityCode
     * @param string $barangayCode
     * @param string|null $purok
     * @return string
     */
    public function formatPhilsysAddress(
        string $regionCode,
        string $provinceCode,
        string $cityCode,
        string $barangayCode,
        ?string $purok = null
    ): string {
        $address = $this->getAddressFromCodes(
            $regionCode,
            $provinceCode,
            $cityCode,
            $barangayCode
        );

        $parts = array_filter([
            $purok ? "Purok {$purok}" : null,
            "Barangay {$address['barangay']}",
            $address['city'],
            $address['province'],
            $address['region'],
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get address codes for Philsys integration payload.
     * Returns an array structure suitable for Philsys API calls.
     * 
     * @param \App\Models\Resident $resident
     * @return array
     */
    public function getPhilsysAddressPayload($resident): array
    {
        return [
            'psgc' => [
                'region' => $resident->region_psgc_code,
                'province' => $resident->province_psgc_code,
                'city' => $resident->city_psgc_code,
                'barangay' => $resident->barangay_psgc_code,
            ],
            'address' => [
                'purok' => $resident->purok,
                'barangay' => $resident->barangay,
                'municipality' => $resident->municipality,
                'province' => $resident->province,
                'postal_code' => $resident->postal_code,
            ],
            'formatted' => $this->formatPhilsysAddress(
                $resident->region_psgc_code ?? self::BUGUEY_REGION_CODE,
                $resident->province_psgc_code ?? self::BUGUEY_PROVINCE_CODE,
                $resident->city_psgc_code ?? self::BUGUEY_CITY_CODE,
                $resident->barangay_psgc_code ?? '',
                $resident->purok
            ),
        ];
    }
}
