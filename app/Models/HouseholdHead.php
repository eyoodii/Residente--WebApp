<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * HouseholdHead Model (HHN - Household Head Number)
 * Represents a family unit within a physical household.
 * This is the SECONDARY level in the hierarchy: HN → HHN → HHM
 * Multiple HHNs can exist in one HN (e.g., two families in one house)
 */
class HouseholdHead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_id',
        'resident_id',
        'head_first_name',
        'head_last_name',
        'head_extension_name',
        'household_head_number',
        'surname',
        'family_size',
        'family_name',
        'is_primary_family',
        'is_active',
        'is_4ps_beneficiary',
        'assistance_programs',
    ];

    protected $casts = [
        'family_size' => 'integer',
        'is_primary_family' => 'boolean',
        'is_active' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'assistance_programs' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate household head number
        static::creating(function ($head) {
            if (empty($head->household_head_number)) {
                $head->household_head_number = self::generateHeadNumber($head->household_id);
            }
            
            // Set surname from resident (or manual name) if not provided
            if (empty($head->surname)) {
                if ($head->resident_id) {
                    $resident = Resident::find($head->resident_id);
                    if ($resident) {
                        $head->surname = $resident->last_name;
                    }
                } elseif (!empty($head->head_last_name)) {
                    $head->surname = $head->head_last_name;
                }
            }

            // Check if this is the first family at this address
            $existingHeads = self::where('household_id', $head->household_id)->count();
            if ($existingHeads === 0) {
                $head->is_primary_family = true;
            }
        });

        // Update family size when members change
        static::created(function ($head) {
            $head->updateFamilySize();
        });
    }

    /**
     * Generate Household Head Number (HHN)
     * Format: HHN-NN (e.g., HHN-01, HHN-02)
     * Includes soft-deleted records to prevent duplicate numbers
     */
    public static function generateHeadNumber(int $householdId): string
    {
        // Include soft-deleted records to prevent number reuse
        $highestNumber = self::withTrashed()
            ->where('household_id', $householdId)
            ->pluck('household_head_number')
            ->filter(function ($hhn) {
                // Extract numeric part and ensure it's valid
                return preg_match('/^HHN-(\d+)$/', $hhn, $matches);
            })
            ->map(function ($hhn) {
                preg_match('/^HHN-(\d+)$/', $hhn, $matches);
                return (int) $matches[1];
            })
            ->max();

        // Use the highest number + 1, or 1 if no previous records
        $nextNumber = ($highestNumber ?? 0) + 1;
        return 'HHN-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Get the physical household (address)
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the resident who is the head of this family
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get all members of this family unit
     */
    public function members(): HasMany
    {
        return $this->hasMany(HouseholdMember::class);
    }

    /**
     * Get all registered residents linked to this family
     */
    public function linkedResidents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get the full HHN identifier including HN
     */
    public function getFullIdentifierAttribute(): string
    {
        return $this->household->household_number . ' / ' . $this->household_head_number;
    }

    /**
     * Get the head's full name (from linked resident or manual entry)
     */
    public function getHeadNameAttribute(): string
    {
        if ($this->resident) {
            return $this->resident->full_name;
        }

        $parts = array_filter([
            $this->head_first_name ?? '',
            $this->head_last_name ?? '',
            $this->head_extension_name ?? '',
        ]);
        $manual = trim(implode(' ', $parts));
        return $manual !== '' ? $manual : 'Unknown';
    }

    /**
     * Update the family size based on members
     */
    public function updateFamilySize(): void
    {
        // Count: 1 (head) + all members
        $memberCount = $this->members()->count();
        $this->update(['family_size' => 1 + $memberCount]);
    }

    /**
     * Find potential members by surname at the same household
     * This implements the "Surname Recognition" logic
     */
    public function findPotentialMembers()
    {
        return Resident::where('last_name', $this->surname)
            ->where('household_id', $this->household_id)
            ->where('id', '!=', $this->resident_id)
            ->whereNull('household_head_id')
            ->get();
    }

    /**
     * Auto-link a resident to this family based on surname
     */
    public function autoLinkResident(Resident $resident): bool
    {
        if (strtolower($resident->last_name) !== strtolower($this->surname)) {
            return false;
        }

        $resident->update([
            'household_head_id' => $this->id,
        ]);

        // Update family size
        $this->updateFamilySize();

        return true;
    }

    /**
     * Find matching household heads for a new resident
     * Used during registration for surname-based auto-linking
     */
    public static function findMatchingHeadsForResident(Resident $resident): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('surname', $resident->last_name)
            ->whereHas('household', function ($query) use ($resident) {
                $query->where('barangay', $resident->barangay)
                      ->where('purok', $resident->purok);
            })
            ->with(['household', 'resident'])
            ->get();
    }

    /**
     * Scope for active household heads
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searching by surname or head name
     */
    public function scopeSearchName($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('surname', 'like', "%{$search}%")
              ->orWhere('family_name', 'like', "%{$search}%")
              ->orWhere('household_head_number', 'like', "%{$search}%")
              ->orWhereHas('resident', function ($rq) use ($search) {
                  $rq->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Get all members including the head for aid distribution
     */
    public function getAllFamilyMembers()
    {
        // Get the head
        $members = collect([$this->resident]);
        
        // Add registered residents linked to this family
        $linkedResidents = Resident::where('household_head_id', $this->id)
            ->where('id', '!=', $this->resident_id)
            ->get();
        $members = $members->merge($linkedResidents);
        
        // Add non-registered household members
        $householdMembers = $this->members()->get();
        
        return [
            'registered_members' => $members,
            'unregistered_members' => $householdMembers,
            'total_count' => $members->count() + $householdMembers->count(),
        ];
    }
}
