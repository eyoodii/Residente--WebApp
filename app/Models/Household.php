<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Household Model (HN - Household Number)
 * Represents a physical address/house in the LGU system.
 * This is the PRIMARY level in the hierarchy: HN → HHN → HHM
 */
class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_number',
        'house_number',
        'street',
        'purok',
        'barangay',
        'municipality',
        'province',
        'full_address',
        'latitude',
        'longitude',
        'housing_type',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate household number and full address
        static::creating(function ($household) {
            if (empty($household->household_number)) {
                $household->household_number = self::generateHouseholdNumber();
            }
            $household->full_address = $household->buildFullAddress();
        });

        static::updating(function ($household) {
            $household->full_address = $household->buildFullAddress();
        });
    }

    /**
     * Generate a unique Household Number (HN)
     * Format: HN-YYYY-NNNN (e.g., HN-2026-0001)
     */
    public static function generateHouseholdNumber(): string
    {
        $year = date('Y');
        $prefix = "HN-{$year}-";
        
        $lastHousehold = self::where('household_number', 'like', "{$prefix}%")
            ->orderBy('household_number', 'desc')
            ->first();

        if ($lastHousehold) {
            $lastNumber = (int) Str::afterLast($lastHousehold->household_number, '-');
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Build full address string for display and search
     */
    public function buildFullAddress(): string
    {
        $parts = array_filter([
            $this->house_number,
            $this->street,
            "Purok {$this->purok}",
            "Brgy. {$this->barangay}",
            $this->municipality,
            $this->province,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get all household heads (families) at this address
     */
    public function householdHeads(): HasMany
    {
        return $this->hasMany(HouseholdHead::class);
    }

    /**
     * Get all residents living at this address
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get all families (HHN level) at this address
     * NEW: For Super Admin hierarchical data collection
     */
    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    /**
     * Get the total number of families at this address
     */
    public function getFamilyCountAttribute(): int
    {
        return $this->householdHeads()->count();
    }

    /**
     * Get the total number of residents at this address
     */
    public function getTotalResidentsAttribute(): int
    {
        return $this->residents()->count();
    }

    /**
     * Get the primary family (first registered household head)
     */
    public function getPrimaryFamilyAttribute(): ?HouseholdHead
    {
        return $this->householdHeads()->where('is_primary_family', true)->first()
            ?? $this->householdHeads()->oldest()->first();
    }

    /**
     * Scope for active households
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searching by address
     */
    public function scopeSearchAddress($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('full_address', 'like', "%{$search}%")
              ->orWhere('household_number', 'like', "%{$search}%")
              ->orWhere('barangay', 'like', "%{$search}%")
              ->orWhere('purok', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for filtering by barangay
     */
    public function scopeInBarangay($query, string $barangay)
    {
        return $query->where('barangay', $barangay);
    }

    /**
     * Find or create household by address
     */
    public static function findOrCreateByAddress(array $addressData): self
    {
        // Search for existing household with same address components
        $existing = self::where('purok', $addressData['purok'] ?? null)
            ->where('barangay', $addressData['barangay'] ?? null)
            ->where('house_number', $addressData['house_number'] ?? null)
            ->where('street', $addressData['street'] ?? null)
            ->first();

        if ($existing) {
            return $existing;
        }

        return self::create($addressData);
    }
}
