<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseholdProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resident_id',
        'housing_type',
        'dwelling_type',
        'number_of_rooms',
        'has_electricity',
        'has_water_supply',
        'water_source',
        'toilet_facility',
        'has_internet_access',
        'has_television',
        'has_radio',
        'total_household_income',
        'income_classification',
        'owns_vehicle',
        'vehicle_types',
        'owns_agricultural_land',
        'agricultural_land_area',
        'special_needs',
        'assistance_received',
    ];

    protected $casts = [
        'has_electricity' => 'boolean',
        'has_water_supply' => 'boolean',
        'has_internet_access' => 'boolean',
        'has_television' => 'boolean',
        'has_radio' => 'boolean',
        'total_household_income' => 'decimal:2',
        'owns_vehicle' => 'boolean',
        'owns_agricultural_land' => 'boolean',
        'agricultural_land_area' => 'decimal:2',
    ];

    /**
     * Get the resident (head of household) that owns this profile
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get vehicle types as array
     */
    public function getVehicleTypesArrayAttribute(): array
    {
        if (!$this->vehicle_types) {
            return [];
        }
        
        return is_array($this->vehicle_types) 
            ? $this->vehicle_types 
            : json_decode($this->vehicle_types, true) ?? [];
    }

    /**
     * Set vehicle types from array
     */
    public function setVehicleTypesAttribute($value): void
    {
        $this->attributes['vehicle_types'] = is_array($value) 
            ? json_encode($value) 
            : $value;
    }

    /**
     * Check if household qualifies as low-income
     */
    public function isLowIncome(): bool
    {
        return in_array($this->income_classification, [
            'Below Poverty Threshold',
            'Low Income'
        ]);
    }

    /**
     * Calculate basic amenities score (0-100)
     */
    public function getBasicAmenitiesScoreAttribute(): int
    {
        $score = 0;
        
        if ($this->has_electricity) $score += 20;
        if ($this->has_water_supply) $score += 20;
        if (in_array($this->toilet_facility, ['Water Sealed Sewer', 'Water Sealed Septic Tank'])) $score += 20;
        if ($this->has_internet_access) $score += 15;
        if ($this->has_television) $score += 10;
        if ($this->has_radio) $score += 10;
        if ($this->dwelling_type !== 'Makeshift/Salvaged') $score += 5;
        
        return min($score, 100);
    }
}
