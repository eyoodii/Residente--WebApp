<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'user_email',
        'user_role',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
        'severity',
        'is_suspicious',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'is_suspicious' => 'boolean',
    ];

    /**
     * Get the resident that performed the action
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Scope for critical actions
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    /**
     * Scope for suspicious activities
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for specific resident
     */
    public function scopeForResident($query, $residentId)
    {
        return $query->where('resident_id', $residentId);
    }

    /**
     * Scope for specific action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific entity
     */
    public function scopeForEntity($query, $entityType, $entityId = null)
    {
        $query->where('entity_type', $entityType);
        
        if ($entityId) {
            $query->where('entity_id', $entityId);
        }
        
        return $query;
    }

    /**
     * Log an activity
     */
    public static function log(array $data): self
    {
        return self::create(array_merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_url' => request()->fullUrl(),
            'request_method' => request()->method(),
        ], $data));
    }
}
