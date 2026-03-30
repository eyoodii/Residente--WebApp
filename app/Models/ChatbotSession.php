<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'current_flow',
        'current_step',
        'collected_data',
        'status',
    ];

    protected $casts = [
        'collected_data' => 'array',
        'current_step'   => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isInFlow(): bool
    {
        return $this->status === 'active' && !empty($this->current_flow);
    }

    public function storeAnswer(string $key, mixed $value): void
    {
        $data = $this->collected_data ?? [];
        $data[$key] = $value;
        $this->update(['collected_data' => $data]);
    }

    public function advanceStep(): void
    {
        $this->increment('current_step');
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function handOff(): void
    {
        $this->update(['status' => 'handed_off']);
    }
}
