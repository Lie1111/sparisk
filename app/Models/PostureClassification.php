<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostureClassification extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'classification_type', 'classification_name',
        'severity', 'confidence', 'description'
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
