<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * PhilSys Verification Model
 * 
 * Stores verification records for audit trail and compliance purposes.
 * Card images are stored securely in the private disk.
 */
class PhilsysVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'transaction_id',
        'verification_method',
        'result_code',
        'national_id_hash',
        'card_front_path',
        'card_back_path',
        'region_psgc_code',
        'province_psgc_code',
        'city_psgc_code',
        'barangay_psgc_code',
        'qr_data',
        'match_results',
        'address_validation',
        'verified_by_admin_id',
        'admin_notes',
        'ip_address',
        'user_agent',
        'is_successful',
    ];

    protected function casts(): array
    {
        return [
            'qr_data' => 'array',
            'match_results' => 'array',
            'address_validation' => 'array',
            'is_successful' => 'boolean',
        ];
    }

    /**
     * Get the resident that owns this verification record.
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get the admin who performed manual verification (if applicable).
     */
    public function verifiedByAdmin()
    {
        return $this->belongsTo(Resident::class, 'verified_by_admin_id');
    }

    /**
     * Get the file path for the front card image.
     * Note: Private disk doesn't support public URLs, use path instead.
     */
    public function getCardFrontPathAttribute(): ?string
    {
        if (!$this->attributes['card_front_path']) {
            return null;
        }

        return $this->attributes['card_front_path'];
    }

    /**
     * Get the file path for the back card image.
     * Note: Private disk doesn't support public URLs, use path instead.
     */
    public function getCardBackPathAttribute(): ?string
    {
        if (!$this->attributes['card_back_path']) {
            return null;
        }

        return $this->attributes['card_back_path'];
    }

    /**
     * Get the front card image contents for authorized viewing.
     */
    public function getCardFrontContents(): ?string
    {
        if (!$this->card_front_path || !Storage::disk('private')->exists($this->card_front_path)) {
            return null;
        }

        return Storage::disk('private')->get($this->card_front_path);
    }

    /**
     * Get the back card image contents for authorized viewing.
     */
    public function getCardBackContents(): ?string
    {
        if (!$this->card_back_path || !Storage::disk('private')->exists($this->card_back_path)) {
            return null;
        }

        return Storage::disk('private')->get($this->card_back_path);
    }

    /**
     * Scope to get successful verifications only.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope to get failed verifications only.
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Check if verification was done via QR scan.
     */
    public function isQrScan(): bool
    {
        return $this->verification_method === 'qr_scan';
    }

    /**
     * Check if verification was done manually by admin.
     */
    public function isAdminVerified(): bool
    {
        return $this->verification_method === 'admin_manual' && $this->verified_by_admin_id;
    }

    /**
     * Delete card images from storage.
     */
    public function deleteCardImages(): void
    {
        if ($this->card_front_path) {
            Storage::disk('private')->delete($this->card_front_path);
        }

        if ($this->card_back_path) {
            Storage::disk('private')->delete($this->card_back_path);
        }
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Clean up card images when verification record is deleted
        static::deleting(function ($verification) {
            $verification->deleteCardImages();
        });
    }
}
