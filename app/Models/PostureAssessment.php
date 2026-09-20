<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostureAssessment extends Model
{
    protected $fillable = [
        'patient_id', 'health_screening_id', 'assessment_date',
        'time_mark', 'overall_score', 'overall_status',
        'posture_classification', 'review_status',
        'suspected_pattern', 'secondary_pattern', 'asymmetry_flag',
        'confidence_level', 'clinical_summary',
        'primary_findings', 'need_attention', 'biggest_improvement',
        'overall_progress'
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'overall_score' => 'integer',
        'asymmetry_flag' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function healthScreening(): BelongsTo
    {
        return $this->belongsTo(HealthScreening::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(PostureMeasurement::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AssessmentImage::class);
    }

    public function classifications(): HasMany
    {
        return $this->hasMany(PostureClassification::class);
    }

    public function exerciseRecommendations(): HasMany
    {
        return $this->hasMany(ExerciseRecommendation::class);
    }

    public function massageRecommendations(): HasMany
    {
        return $this->hasMany(MassageRecommendation::class);
    }

    public function weeklyPrograms(): HasMany
    {
        return $this->hasMany(WeeklyProgram::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function getMeasurementsByView(string $view)
    {
        return $this->measurements()->where('view', $view)->get();
    }

    public function getPrimaryClassification()
    {
        return $this->classifications()->where('classification_type', 'primary')->first();
    }
}
