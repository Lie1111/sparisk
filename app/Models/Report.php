<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'posture_assessment_id', 'patient_id', 'report_type',
        'file_path', 'report_data', 'generated_at'
    ];

    protected $casts = [
        'report_data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(PostureAssessment::class, 'posture_assessment_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
