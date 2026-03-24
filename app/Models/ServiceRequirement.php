<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'requirement',
        'where_to_secure',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
