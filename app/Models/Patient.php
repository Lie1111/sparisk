<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    protected $fillable = [
        'user_id', 'name', 'photo', 'age', 'gender', 'height', 'weight',
        'state', 'diagnosis', 'emergency_contact_name', 'emergency_contact_phone',
        'special_needs_type', 'archived_at'
    ];

    protected $casts = [
        'age' => 'integer',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'archived_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function healthScreenings(): HasMany
    {
        return $this->hasMany(HealthScreening::class);
    }

    public function postureAssessments(): HasMany
    {
        return $this->hasMany(PostureAssessment::class);
    }

    public function progressRecords(): HasMany
    {
        return $this->hasMany(ProgressRecord::class);
    }

    public function academyMemberships(): HasMany
    {
        return $this->hasMany(AcademyMember::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('diagnosis', 'like', "%{$search}%")
            ->orWhere('state', 'like', "%{$search}%");
    }
}
