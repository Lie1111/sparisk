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
        'special_needs_type', 'neuro_profile', 'neuro_conditions',
        'neuro_conditions_other', 'archived_at'
    ];

    protected $casts = [
        'age' => 'integer',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'neuro_conditions' => 'array',
        'archived_at' => 'datetime',
    ];

    protected $appends = ['neuro_profile_label', 'neuro_condition_labels'];

    /**
     * Display label for the selected Neurodevelopmental Profile, or null when
     * the participant has not answered it yet.
     */
    public function getNeuroProfileLabelAttribute(): ?string
    {
        if ($this->neuro_profile === null) {
            return null;
        }

        return config("sparisk.neuro_profiles.{$this->neuro_profile}.label");
    }

    /**
     * Display labels for the selected conditions. Labels come from
     * config('sparisk.neuro_conditions') so every platform shows the same text.
     */
    public function getNeuroConditionLabelsAttribute(): array
    {
        return collect($this->neuro_conditions ?? [])
            ->map(fn ($key) => config("sparisk.neuro_conditions.{$key}.label") ?? $key)
            ->values()
            ->all();
    }

    /**
     * True once the participant has answered the Neurodevelopmental Profile, so
     * the app can ask only once instead of on every assessment.
     */
    public function hasNeuroProfile(): bool
    {
        return $this->neuro_profile !== null;
    }

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
