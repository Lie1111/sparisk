<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MassageRecommendation extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'body_area', 'program', 'priority_stars',
        'instructions', 'duration_minutes', 'frequency',
        'video_url', 'image_url', 'safety_notes', 'order_index'
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
