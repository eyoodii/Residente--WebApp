<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseholdMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resident_id',
        'household_head_id',
        'member_number',
        'is_auto_linked',
        'linked_resident_id',
        'link_status',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'relationship',
        'civil_status',
        'occupation',
        'monthly_income',
        'educational_attainment',
        'is_pwd',
        'is_senior_citizen',
        'is_solo_parent',
        'is_indigenous_people',
        'is_4ps_beneficiary',
        'is_active_ofw',
        'ofw_country',
        'ofw_nature_of_work',
        'ofw_year_deployed',
        'is_returned_ofw',
        'ofw_year_returned',
        'ofw_nature_of_return',
        'is_local_migrant',
        'local_migrant_location',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_income' => 'decimal:2',
        'is_pwd' => 'boolean',
        'is_senior_citizen' => 'boolean',
        'is_solo_parent' => 'boolean',
        'is_indigenous_people' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'is_active_ofw' => 'boolean',
        'ofw_year_deployed' => 'integer',
        'is_returned_ofw' => 'boolean',
        'ofw_year_returned' => 'integer',
        'is_local_migrant' => 'boolean',
        'is_auto_linked' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate member number
        static::creating(function ($member) {
            if (empty($member->member_number) && $member->household_head_id) {
                $member->member_number = self::generateMemberNumber($member->household_head_id);
            }
        });

        // Update family size on HouseholdHead
        static::created(function ($member) {
            if ($member->householdHead) {
                $member->householdHead->updateFamilySize();
            }
        });

        static::deleted(function ($member) {
            if ($member->householdHead) {
                $member->householdHead->updateFamilySize();
            }
        });
    }

    /**
     * Generate Member Number (HHM)
     * Format: HHM-NNN (e.g., HHM-001, HHM-002)
     */
    public static function generateMemberNumber(int $householdHeadId): string
    {
        $count = self::where('household_head_id', $householdHeadId)->count();
        return 'HHM-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the resident (head of household) that owns this member (legacy)
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get the household head (family unit) this member belongs to
     */
    public function householdHead(): BelongsTo
    {
        return $this->belongsTo(HouseholdHead::class);
    }

    /**
     * Get the linked resident account if member has registered
     */
    public function linkedResident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'linked_resident_id');
    }

    /**
     * Get the household through the household head
     */
    public function getHouseholdAttribute(): ?Household
    {
        return $this->householdHead?->household;
    }

    /**
     * Get the full identifier (HN / HHN / HHM)
     */
    public function getFullIdentifierAttribute(): string
    {
        if (!$this->householdHead) {
            return $this->member_number ?? 'N/A';
        }
        return $this->householdHead->full_identifier . ' / ' . $this->member_number;
    }

    /**
     * Get the full name of the household member
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    /**
     * Get the age of the household member
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Check if member qualifies for any assistance program
     */
    public function needsAssistance(): bool
    {
        return $this->is_pwd || 
               $this->is_senior_citizen || 
               $this->is_solo_parent || 
               $this->is_indigenous_people;
    }

    /**
     * Check if member is an OFW (active or returned)
     */
    public function isOFW(): bool
    {
        return $this->is_active_ofw || $this->is_returned_ofw;
    }

    /**
     * Check if member is a migrant worker (OFW or local)
     */
    public function isMigrantWorker(): bool
    {
        return $this->is_active_ofw || $this->is_returned_ofw || $this->is_local_migrant;
    }

    /**
     * Get OFW status description
     */
    public function getOFWStatusAttribute(): ?string
    {
        if ($this->is_active_ofw) {
            return 'Active OFW' . ($this->ofw_country ? ' in ' . $this->ofw_country : '');
        }
        
        if ($this->is_returned_ofw) {
            return 'Returned OFW' . ($this->ofw_year_returned ? ' (since ' . $this->ofw_year_returned . ')' : '');
        }
        
        if ($this->is_local_migrant) {
            return 'Local Migrant' . ($this->local_migrant_location ? ' in ' . $this->local_migrant_location : '');
        }
        
        return null;
    }
}
