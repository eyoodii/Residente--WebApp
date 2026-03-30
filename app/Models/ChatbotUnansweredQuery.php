<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotUnansweredQuery extends Model
{
    protected $fillable = [
        'session_id',
        'original_message',
        'tier_reached',
        'used_gemini',
        'gemini_response',
        'reviewed_by_admin',
    ];

    protected $casts = [
        'used_gemini'        => 'boolean',
        'reviewed_by_admin'  => 'boolean',
    ];

    public function scopeUnreviewed($query)
    {
        return $query->where('reviewed_by_admin', false);
    }
}
