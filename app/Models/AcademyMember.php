<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyMember extends Model
{
    protected $fillable = [
        'academy_id', 'patient_id', 'role', 'joined_at', 'active'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function academy(): BelongsTo
    {
        return $this->belongsTo(Academy::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
