<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'resident_id',
        'service_id',
        'status',
        'current_step',
        'notes',
        'requested_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'current_step' => 'integer',
        'requested_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($serviceRequest) {
            if (!$serviceRequest->request_number) {
                $serviceRequest->request_number = self::generateRequestNumber();
            }
        });
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in-progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCurrentStepDetails()
    {
        return $this->service->steps()
            ->where('step_number', $this->current_step)
            ->first();
    }

    public static function generateRequestNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        return "SR-{$date}-{$random}";
    }
}
