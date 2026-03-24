<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'department',
        'description',
        'classification',
        'type',
        'who_may_avail',
        'fee',
        'fee_description',
        'processing_time_minutes',
        'icon',
        'color',
        'is_active',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'processing_time_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(ServiceStep::class)->orderBy('step_number');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ServiceRequirement::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    public function getFormattedFeeAttribute(): string
    {
        $fee = (float) ($this->fee ?? 0);
        
        if ($fee == 0) {
            return 'FREE';
        }
        
        if ($this->fee_description) {
            return $this->fee_description;
        }
        
        return '₱' . number_format($fee, 2);
    }

    public function getProcessingTimeFormattedAttribute(): string
    {
        if (!$this->processing_time_minutes) {
            return 'Variable';
        }

        $hours = floor($this->processing_time_minutes / 60);
        $minutes = $this->processing_time_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}hr {$minutes}min";
        } elseif ($hours > 0) {
            return "{$hours}hr";
        } else {
            return "{$minutes}min";
        }
    }
}
