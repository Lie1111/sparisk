<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Age (and, where the evidence requires it, sex) reference for one posture
 * parameter. This is the reference source consumed by
 * {@see \App\Services\Sparisk\SpariskMeasurementEngine}.
 *
 * `reference_source` + `reference_level` record where the reference came from
 * so the deviation on every report is traceable:
 *   - published         : Level 1, from valid published normative data
 *   - sata_validated    : Level 2, from a reference SATA has already validated
 *   - individual_baseline: Level 3, no population reference exists (evidence
 *                          gap) — `reference_value` is NULL and only change
 *                          from the individual's first assessment is reported.
 */
class PostureSetting extends Model
{
    protected $fillable = [
        'view', 'section', 'label', 'age_group',
        'reference_value', 'normal_min', 'normal_max',
        'reference_source', 'reference_level', 'sex_if_required', 'sex',
        'interpretation',
    ];

    protected $casts = [
        'reference_value' => 'decimal:2',
        'normal_min' => 'decimal:2',
        'normal_max' => 'decimal:2',
        'sex_if_required' => 'boolean',
    ];

    /**
     * An approved reference is one backed by published or SATA-validated
     * evidence. Only these may drive On Point / Near Point / Off Point.
     */
    public function hasApprovedReference(): bool
    {
        return in_array($this->reference_level, ['published', 'sata_validated'], true)
            && $this->reference_value !== null;
    }

    /**
     * Tolerance used for the On Point / Near Point / Off Point bands.
     *
     * The tolerance is the reference's own published half-width (the standard
     * deviation published with the reference). No arbitrary ±5° / ±10° cut-off
     * is ever invented: when the reference has no published band the tolerance
     * is NULL and the parameter cannot carry a point status.
     */
    public function tolerance(): ?float
    {
        if ($this->normal_min === null || $this->normal_max === null) {
            return null;
        }

        return round(((float) $this->normal_max - (float) $this->normal_min) / 2, 2);
    }
}
