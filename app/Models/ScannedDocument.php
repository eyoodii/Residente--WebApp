<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScannedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'user_id',
        'document_type',
        'document_path',
        'raw_text',
        'extracted_fields',
        'confidence_score',
        'verification_status',
        'notes',
    ];

    protected $casts = [
        'extracted_fields' => 'array',
        'confidence_score' => 'decimal:2',
    ];

    /**
     * Get the resident associated with this document
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get the user who uploaded this document
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
