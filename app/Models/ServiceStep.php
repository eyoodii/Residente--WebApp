<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'step_number',
        'step_type',
        'description',
        'processing_time_minutes',
        'responsible_person',
        'fee',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'processing_time_minutes' => 'integer',
        'fee' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getIsClientStepAttribute(): bool
    {
        return strtolower($this->step_type) === 'client';
    }

    public function getIsAgencyStepAttribute(): bool
    {
        return strtolower($this->step_type) === 'agency';
    }
}
