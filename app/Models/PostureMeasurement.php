<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single sagittal/frontal posture reading stored as a deviation angle.
 *
 * The authoritative output is `deviation_angle = measured_angle − reference_value`.
 * `reference_value` may be NULL when no published or SATA-validated reference
 * exists for the parameter (evidence gap): in that case the assessment is kept
 * as an individual baseline with `reference_level = individual_baseline` and no
 * population cut-off is invented.
 */
class PostureMeasurement extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'view', 'section', 'label',
        'value', 'measured_angle', 'reference_value', 'deviation', 'deviation_angle',
        'deviation_direction', 'unit', 'severity', 'alignment_status', 'position_note',
        'review_required', 'status_text', 'status_code',
        'reference_source', 'reference_level', 'age_group', 'sex_if_required',
        'posture_classification',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'measured_angle' => 'decimal:2',
        'reference_value' => 'decimal:2',
        'deviation' => 'decimal:2',
        'deviation_angle' => 'decimal:2',
        'review_required' => 'boolean',
    ];

    /**
     * The measurement comes from a parameter that has an approved reference and
     * can therefore carry an On Point / Near Point / Off Point status.
     */
    public function hasApprovedReference(): bool
    {
        return in_array($this->reference_level, ['published', 'sata_validated'], true)
            && $this->reference_value !== null;
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }
}
