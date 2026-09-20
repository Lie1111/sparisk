<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseRecommendation extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'engine', 'program', 'exercise_name', 'program_level',
        'difficulty', 'estimated_duration_minutes', 'video_url', 'image_url',
        'sets_reps', 'progression_stage', 'instructions', 'sets', 'reps',
        'frequency', 'order_index'
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
