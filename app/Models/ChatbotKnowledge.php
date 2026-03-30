<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotKnowledge extends Model
{
    protected $table = 'chatbot_knowledges';

    protected $fillable = [
        'intent_name',
        'category',
        'trigger_keywords_en',
        'trigger_keywords_fil',
        'official_response',
        'response_type',
        'linked_form_flow',
        'is_active',
        'times_matched',
        'last_verified_at',
        'verified_by',
    ];

    protected $casts = [
        'trigger_keywords_en' => 'array',
        'trigger_keywords_fil' => 'array',
        'is_active'           => 'boolean',
        'last_verified_at'    => 'datetime',
    ];

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStale($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('last_verified_at')
              ->orWhere('last_verified_at', '<', now()->subDays(90));
        });
    }

    /** All keywords (EN + FIL) as a flat array for scoring */
    public function allKeywords(): array
    {
        return array_merge(
            $this->trigger_keywords_en ?? [],
            $this->trigger_keywords_fil ?? []
        );
    }

    public function markMatched(): void
    {
        $this->increment('times_matched');
    }
}
