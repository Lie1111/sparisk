<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostureMeasurement extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'view', 'section', 'label',
        'value', 'reference_value', 'deviation', 'deviation_direction', 'unit',
        'severity', 'alignment_status', 'position_note',
        'review_required', 'status_text'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'reference_value' => 'decimal:2',
        'deviation' => 'decimal:2',
        'review_required' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
