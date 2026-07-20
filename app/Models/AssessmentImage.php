<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentImage extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'view', 'image_path', 'order_index'
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
