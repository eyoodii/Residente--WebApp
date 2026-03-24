<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Family Model (HHN - Household Head Number)
 * Represents a family unit within a physical household
 * This is the MIDDLE level in the hierarchy: HN → HHN → HHM
 */
class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_id',
        'hhn_number',
        'head_surname',
        'household_head_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate HHN number
        static::creating(function ($family) {
            if (empty($family->hhn_number)) {
                $family->hhn_number = self::generateHHNNumber();
            }
        });
    }

    /**
     * Generate a unique Household Head Number (HHN)
     * Format: HHN-YYYY-NNNN (e.g., HHN-2026-0001)
     */
    public static function generateHHNNumber(): string
    {
        $year = date('Y');
        $prefix = "HHN-{$year}-";
        
        $lastFamily = self::where('hhn_number', 'like', "{$prefix}%")
            ->orderBy('hhn_number', 'desc')
            ->first();

        if ($lastFamily) {
            $lastNumber = (int) Str::afterLast($lastFamily->hhn_number, '-');
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the household (physical address) this family belongs to
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get all members (residents) of this family
     */
    public function members(): HasMany
    {
        return $this->hasMany(Resident::class, 'family_id');
    }

    /**
     * Get the household head (designated family leader)
     */
    public function householdHead(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'household_head_id');
    }

    /**
     * Get members that were auto-linked and need admin review
     */
    public function autoLinkedMembers(): HasMany
    {
        return $this->members()->where('is_auto_linked', true);
    }

    /**
     * Get verified members (not auto-linked or manually confirmed)
     */
    public function verifiedMembers(): HasMany
    {
        return $this->members()->where('is_auto_linked', false);
    }

    /**
     * Get the full address through the household relationship
     */
    public function getFullAddressAttribute(): string
    {
        return $this->household ? $this->household->full_address : '';
    }

    /**
     * Get the barangay through the household relationship
     */
    public function getBarangayAttribute(): string
    {
        return $this->household ? $this->household->barangay : '';
    }
}
