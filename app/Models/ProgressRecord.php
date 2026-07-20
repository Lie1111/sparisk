<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressRecord extends Model
{
    protected $fillable = [
        'patient_id', 'from_assessment_id', 'to_assessment_id',
        'improvement_fields', 'attention_fields', 'overall_progress',
        'comparison_image_path', 'summary'
    ];

    protected $casts = [
        'improvement_fields' => 'array',
        'attention_fields' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function fromAssessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'from_assessment_id');
    }

    public function toAssessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'to_assessment_id');
    }
}
