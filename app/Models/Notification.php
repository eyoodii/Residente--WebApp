<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'title',
        'message',
        'type',
        'related_entity_type',
        'related_entity_id',
        'action_url',
        'action_label',
        'is_read',
        'read_at',
        'priority',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    /**
     * Get the resident that owns this notification
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for urgent notifications
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Create a notification for a resident
     */
    public static function notify($residentId, array $data): self
    {
        return self::create(array_merge([
            'resident_id' => $residentId,
        ], $data));
    }

    /**
     * Create notification for service request update
     */
    public static function serviceUpdate($residentId, $serviceRequest, $message): self
    {
        return self::notify($residentId, [
            'title' => 'Service Request Update',
            'message' => $message,
            'type' => 'service_update',
            'related_entity_type' => 'ServiceRequest',
            'related_entity_id' => $serviceRequest->id,
            'action_url' => route('service-request.show', $serviceRequest->request_number),
            'action_label' => 'View Request',
            'priority' => 'normal',
        ]);
    }

    /**
     * Create notification for document ready for pickup
     */
    public static function documentReady($residentId, $serviceRequest): self
    {
        return self::notify($residentId, [
            'title' => 'Document Ready for Pickup',
            'message' => "Your {$serviceRequest->service->name} is ready for pickup at the Barangay Hall.",
            'type' => 'document_ready',
            'related_entity_type' => 'ServiceRequest',
            'related_entity_id' => $serviceRequest->id,
            'action_url' => route('service-request.show', $serviceRequest->request_number),
            'action_label' => 'View Details',
            'priority' => 'high',
        ]);
    }
}
