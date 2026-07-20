<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostureMeasurement extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'view', 'section', 'label',
        'value', 'unit', 'severity', 'status_text'
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
