<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyProgram extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'week_number', 'day_of_week',
        'activity_type', 'activity_title', 'activity_details',
        'duration_minutes', 'exercise_ids', 'order_index'
    ];

    protected $casts = [
        'week_number' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
