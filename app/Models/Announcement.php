<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'category',
        'target_barangay',
        'posted_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'posted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include published (active) announcements.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
                     ->where('posted_at', '<=', now());
    }

    /**
     * Scope a query to include announcements for a specific barangay or all barangays.
     */
    public function scopeForResident(Builder $query, ?string $barangay): Builder
    {
        return $query->where(function ($q) use ($barangay) {
            $q->whereNull('target_barangay')  // For all barangays
              ->orWhere('target_barangay', $barangay); // For specific barangay
        });
    }

    /**
     * Get the badge color class based on category.
     */
    public function getCategoryBadgeColorAttribute(): string
    {
        return match($this->category) {
            'LGU Memorandum' => 'bg-deep-forest bg-opacity-10 text-deep-forest',
            'Barangay News' => 'bg-golden-glow bg-opacity-20 text-deep-forest',
            'Health Service' => 'bg-blue-100 text-blue-800',
            'General Update' => 'bg-sea-green bg-opacity-20 text-sea-green',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the timeline dot color based on category.
     */
    public function getTimelineDotColorAttribute(): string
    {
        return match($this->category) {
            'LGU Memorandum' => 'bg-tiger-orange',
            'Barangay News' => 'bg-sea-green',
            'Health Service' => 'bg-blue-500',
            'General Update' => 'bg-golden-glow',
            default => 'bg-gray-400',
        };
    }

    /**
     * Get formatted time ago string.
     */
    public function getFormattedPostedAtAttribute(): string
    {
        return $this->posted_at->diffForHumans();
    }
}
