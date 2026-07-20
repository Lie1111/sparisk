<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureAssessment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function show(PostureAssessment $postureAssessment)
    {
        $postureAssessment->load([
            'patient',
            'healthScreening',
            'measurements',
            'images',
            'classifications',
            'exerciseRecommendations',
            'massageRecommendations',
            'weeklyPrograms',
            'reports'
        ]);

        $viewMeasurements = [];
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $viewMeasurements[$view] = $postureAssessment->measurements
                ->where('view', $view)
                ->values();
        }

        return Inertia::render('assessments/show', [
            'assessment' => $postureAssessment,
            'viewMeasurements' => $viewMeasurements,
        ]);
    }
}
