<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthScreening extends Model
{
    protected $fillable = [
        'patient_id', 'fear_of_water', 'history_of_seizure', 'heart_disease',
        'asthma', 'neck_pain', 'back_pain', 'hip_pain',
        'can_follow_instruction', 'can_stand_independently',
        'safety_level', 'notes'
    ];

    protected $casts = [
        'fear_of_water' => 'boolean',
        'history_of_seizure' => 'boolean',
        'heart_disease' => 'boolean',
        'asthma' => 'boolean',
        'neck_pain' => 'boolean',
        'back_pain' => 'boolean',
        'hip_pain' => 'boolean',
        'can_follow_instruction' => 'boolean',
        'can_stand_independently' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function computeSafetyLevel(): string
    {
        $highRisk = [
            $this->history_of_seizure,
            $this->heart_disease,
            !$this->can_follow_instruction,
            !$this->can_stand_independently,
        ];

        $mediumRisk = [
            $this->fear_of_water,
            $this->asthma,
            $this->neck_pain,
            $this->back_pain,
            $this->hip_pain,
        ];

        if (count(array_filter($highRisk)) > 1) {
            return 'low';
        }
        if (count(array_filter($highRisk)) === 1 || count(array_filter($mediumRisk)) >= 3) {
            return 'medium';
        }
        return 'high';
    }
}
