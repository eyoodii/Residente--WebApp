<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ChatbotApiKey extends Model
{
    protected $fillable = [
        'name',
        'key',
        'plain_key_prefix',
        'created_by',
        'last_used_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
        'is_active'    => 'boolean',
    ];

    protected $hidden = ['key'];

    // ── Relationships ────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Factory ──────────────────────────────────────────────────────────

    /**
     * Generate a new API key. Returns the plain-text key (shown once).
     */
    public static function generate(string $name, int $createdBy, ?\DateTimeInterface $expiresAt = null): array
    {
        $plainKey = 'rbot_' . Str::random(48);

        $model = static::create([
            'name'             => $name,
            'key'              => hash('sha256', $plainKey),
            'plain_key_prefix' => substr($plainKey, 0, 8),
            'created_by'       => $createdBy,
            'expires_at'       => $expiresAt,
        ]);

        return ['model' => $model, 'plain_key' => $plainKey];
    }

    // ── Lookup ───────────────────────────────────────────────────────────

    /**
     * Find an active, non-expired key by its plain text value.
     */
    public static function findByPlainKey(string $plainKey): ?static
    {
        $hash = hash('sha256', $plainKey);

        return static::where('key', $hash)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
