<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AssessmentImage;
use App\Models\PostureAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function storeCapture(Request $request, PostureAssessment $postureAssessment)
    {
        $request->validate([
            'view' => 'required|in:front,back,right_side,left_side',
            'image' => 'required|string',
            'landmarks' => 'nullable|array',
            'landmarks.*.x' => 'required|numeric',
            'landmarks.*.y' => 'required|numeric',
            'landmarks.*.z' => 'nullable|numeric',
            'landmarks.*.visibility' => 'nullable|numeric',
        ]);

        // Decode base64 image
        $imageData = $request->input('image');
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedImage = base64_decode($imageData);

        // Generate path
        $existingCount = $postureAssessment->images()->where('view', $request->view)->count();
        $filename = 'assessments/' . $postureAssessment->id . '/' . $request->view . '_' . ($existingCount + 1) . '_' . time() . '.jpg';

        Storage::disk('public')->put($filename, $decodedImage);

        $image = AssessmentImage::create([
            'posture_assessment_id' => $postureAssessment->id,
            'view' => $request->view,
            'image_path' => $filename,
            'landmarks' => $request->landmarks,
            'order_index' => $existingCount,
        ]);

        return redirect()->back()->with('success', 'Image and landmarks saved.');
    }
}
