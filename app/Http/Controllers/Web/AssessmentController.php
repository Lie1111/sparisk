<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AssessmentImage;
use App\Models\PostureAssessment;
use App\Services\Sparisk\SpariskInterpretationEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function __construct(private SpariskInterpretationEngine $interpretationEngine) {}

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

        $viewMeasurements = $this->interpretationEngine->measurementsByView($postureAssessment);

        return Inertia::render('assessments/show', [
            'assessment' => $postureAssessment,
            'viewMeasurements' => $viewMeasurements,
            'classification' => $this->interpretationEngine->classificationSummary($postureAssessment),
        ]);
    }

    /**
     * Download a Word (.doc) version of the assessment report for easy sharing
     * with parents / professionals. The document is a Word-compatible HTML file
     * (no extra package required) and is opened natively by MS Word.
     */
    public function generateWord(PostureAssessment $postureAssessment)
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
        ]);

        $viewMeasurements = [];
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $viewMeasurements[$view] = $postureAssessment->measurements
                ->where('view', $view)
                ->values();
        }

        $html = $this->buildWordHtml($postureAssessment, $viewMeasurements);

        $filename = 'SPARISK_'
            . preg_replace('/[^A-Za-z0-9_\-]/', '_', $postureAssessment->patient->name)
            . '_' . $postureAssessment->time_mark . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function buildWordHtml(PostureAssessment $a, array $viewMeasurements): string
    {
        $patient = $a->patient;
        $viewLabels = [
            'front' => 'Front View (A)',
            'back' => 'Back View (B)',
            'right_side' => 'Right Side (C)',
            'left_side' => 'Left Side (D)',
        ];

        $h = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word">';
        $h .= '<head><meta charset="utf-8"><title>SPARISK Assessment</title>';
        $h .= '<style>'
            . 'body{font-family:Calibri,Arial,sans-serif;font-size:11pt;color:#0A3C50;}'
            . 'h1{font-size:18pt;color:#0E7C86;} h2{font-size:13pt;color:#0E7C86;border-bottom:2px solid #0E7C86;padding-bottom:2px;margin-top:18px;}'
            . 'table{border-collapse:collapse;width:100%;margin:6px 0;} th,td{border:1px solid #cbd5e1;padding:4px 8px;font-size:10pt;text-align:left;}'
            . 'th{background:#f1f5f9;} .score{font-size:26pt;font-weight:bold;} .badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:9pt;}'
            . '.muted{color:#64748b;font-size:9pt;} li{margin:2px 0;}'
            . '</style></head><body>';

        $h .= '<h1>SPARISK Posture Assessment Report</h1>';
        $h .= '<p class="muted">Generated: ' . now()->format('d/m/Y H:i') . '</p>';

        // Participant profile
        $h .= '<h2>Participant Profile</h2><table>';
        $h .= '<tr><th style="width:30%">Name</th><td>' . e($patient->name) . '</td></tr>';
        $h .= '<tr><th>Age / Gender</th><td>' . e(($patient->age ? $patient->age . ' years' : '-') . ' / ' . ($patient->gender ?? '-')) . '</td></tr>';
        $h .= '<tr><th>Height / Weight</th><td>' . e(($patient->height ? $patient->height . ' cm' : '-') . ' / ' . ($patient->weight ? $patient->weight . ' kg' : '-')) . '</td></tr>';
        $h .= '<tr><th>Diagnosis</th><td>' . e($patient->diagnosis ?? '-') . '</td></tr>';
        $h .= '</table>';

        // Score
        $score = (int) $a->overall_score;
        $h .= '<h2>Posture Score</h2>';
        $h .= '<p><span class="score">' . $score . '</span> / 100 &nbsp; <span class="badge">' . e($a->overall_status) . '</span></p>';
        $h .= '<p><strong>Date:</strong> ' . e($a->assessment_date->format('d/m/Y')) . ' &nbsp; <strong>Time Mark:</strong> ' . e($a->time_mark) . '</p>';
        if ($a->clinical_summary) {
            $h .= '<p><strong>Clinical Interpretation:</strong> ' . e($a->clinical_summary) . '</p>';
        }

        // Images
        if ($a->images->isNotEmpty()) {
            $h .= '<h2>Posture Images</h2><table>';
            foreach ($a->images as $img) {
                $url = $img->image_path ? asset('storage/' . $img->image_path) : null;
                $h .= '<tr><th style="width:25%">' . e($viewLabels[$img->view] ?? $img->view) . '</th>';
                $h .= '<td>' . ($url ? '<img src="' . e($url) . '" style="max-height:160px;" />' : '<span class="muted">No image</span>') . '</td></tr>';
            }
            $h .= '</table>';
        }

        // Measurements by view
        $h .= '<h2>Measurements by View</h2>';
        foreach ($viewMeasurements as $view => $measurements) {
            $h .= '<h3>' . e($viewLabels[$view] ?? $view) . '</h3><table>';
            $h .= '<tr><th style="width:12%">Code</th><th>Parameter</th><th style="width:15%">Value</th><th>Alignment Status</th><th>Position Note</th></tr>';
            if ($measurements->isEmpty()) {
                $h .= '<tr><td colspan="5">No measurements</td></tr>';
            } else {
                foreach ($measurements as $m) {
                    $label = $m->label;
                    if (!$label) {
                        $def = config('sparisk.measurements.' . $m->section);
                        $label = $def['label'] ?? $m->section;
                    }
                    $status = $m->review_required ? 'review' : ($m->alignment_status ?? $m->severity ?? 'normal');
                    $meta = config("sparisk.alignment_statuses.{$status}", config('sparisk.alignment_statuses.normal'));
                    $col = $this->alignmentHex($meta['color']);
                    $note = $m->review_required
                        ? config('sparisk.alignment_status_descriptions.review')
                        : ($m->position_note ?? '');

                    $h .= '<tr><td>' . e($m->section) . '</td><td>' . e($label) . '</td>';
                    $h .= '<td>' . e((string) number_format((float) $m->value, 1)) . '°</td>';
                    $h .= '<td style="color:' . $col . '">' . e($meta['label']) . '</td>';
                    $h .= '<td>' . e($note) . '</td></tr>';
                }
            }
            $h .= '</table>';
        }

        // Classification
        $h .= '<h2>Posture Classification</h2>';
        $classification = $this->interpretationEngine->classificationSummary($a);
        if ($classification['review_required']) {
            $h .= '<p><strong>' . e($classification['display']) . '</strong></p>';
            $h .= '<ul>';
            if ($classification['review_status']) {
                $h .= '<li>Review status: <strong>Measurement Review Required</strong></li>';
            }
            if ($classification['suspected_pattern']) {
                $h .= '<li>Main finding: ' . e($classification['suspected_pattern_label']
                    ?? 'Possible ' . str_replace('_', ' ', $classification['suspected_pattern']) . ' pattern') . '</li>';
            }
            if ($classification['secondary_pattern']) {
                $h .= '<li>Secondary finding: ' . e($classification['secondary_pattern_label']
                    ?? str_replace('_', ' ', $classification['secondary_pattern'])) . '</li>';
            }
            $h .= '<li>Asymmetry: ' . ($classification['asymmetry_flag'] ? 'Detected' : 'Not detected') . '</li>';
            if ($classification['confidence_level']) {
                $h .= '<li>Confidence: ' . e($classification['confidence_label'] ?? $classification['confidence_level']) . '</li>';
            }
            $h .= '</ul>';
            $h .= '<p class="muted">The measurements currently available are not sufficient to confirm a posture type. No Swayback, Lordosis or Kyphosis diagnosis has been made.</p>';
        } else {
            $h .= '<p><strong>' . e($classification['display']) . '</strong></p>';
        }

        if ($a->classifications->isNotEmpty()) {
            $h .= '<ul>';
            foreach ($classification['classifications'] as $c) {
                $h .= '<li><strong>' . e($c['classification_label'] ?? str_replace('_', ' ', $c['classification_name'])) . '</strong>'
                    . (!empty($c['description']) ? ' — ' . e($c['description']) : '')
                    . (!empty($c['alignment_label']) ? ' <span class="muted">[' . e($c['alignment_label']) . ']</span>' : '')
                    . ' <span class="muted">[' . e($c['classification_type']) . ']</span></li>';
            }
            $h .= '</ul>';
        }

        // Findings
        if ($a->primary_findings) {
            $findings = json_decode($a->primary_findings, true) ?: [];
            if ($findings) {
                $h .= '<h2>Primary Findings</h2><ul>';
                foreach ($findings as $f) {
                    $title = is_array($f) ? ($f['title'] ?? ($f['name'] ?? json_encode($f))) : $f;
                    $h .= '<li>' . e(is_string($title) ? $title : '') . '</li>';
                }
                $h .= '</ul>';
            }
        }

        // Aquatic exercise recommendations
        $h .= '<h2>Aquatic Exercise Recommendations</h2>';
        if ($a->exerciseRecommendations->isEmpty()) {
            $h .= '<p class="muted">No recommendations</p>';
        } else {
            $h .= '<table><tr><th>Exercise</th><th>Level</th><th>Difficulty</th><th>Duration</th><th>Instructions</th></tr>';
            foreach ($a->exerciseRecommendations as $e) {
                $h .= '<tr><td>' . e($e->exercise_name) . '</td><td>' . e($e->program_level) . '</td>'
                    . '<td>' . e($e->difficulty) . '</td><td>' . e($e->estimated_duration_minutes . ' min') . '</td>'
                    . '<td>' . e($e->instructions) . '</td></tr>';
            }
            $h .= '</table>';
        }

        // Massage recommendations
        $h .= '<h2>Massage Therapy Plan</h2>';
        if ($a->massageRecommendations->isEmpty()) {
            $h .= '<p class="muted">No recommendations</p>';
        } else {
            $h .= '<table><tr><th>Body Area</th><th>Duration</th><th>Frequency</th><th>Priority</th><th>Instructions</th></tr>';
            foreach ($a->massageRecommendations as $m) {
                $h .= '<tr><td>' . e($m->body_area) . '</td><td>' . e($m->duration_minutes . ' min') . '</td>'
                    . '<td>' . e($m->frequency) . '</td><td>' . e($m->priority_stars . '/5') . '</td>'
                    . '<td>' . e($m->instructions) . '</td></tr>';
            }
            $h .= '</table>';
        }

        // Weekly program
        $h .= '<h2>Weekly Program</h2>';
        if ($a->weeklyPrograms->isEmpty()) {
            $h .= '<p class="muted">No weekly program</p>';
        } else {
            $h .= '<table><tr><th>Day</th><th>Activity</th><th>Details</th><th>Duration</th></tr>';
            foreach ($a->weeklyPrograms as $p) {
                $h .= '<tr><td>' . e(ucfirst($p->day_of_week)) . '</td><td>' . e($p->activity_title ?? str_replace('_', ' ', $p->activity_type)) . '</td>'
                    . '<td>' . e($p->activity_details) . '</td><td>' . e($p->duration_minutes . ' min') . '</td></tr>';
            }
            $h .= '</table>';
        }

        $h .= '<p style="margin-top:24px" class="muted">This report was generated by SPARISK — SATA Posture Screening &amp; Aquatic Therapy platform.</p>';
        $h .= '</body></html>';

        return $h;
    }

    /**
     * Hex colour for an alignment status band name, used by the Word export.
     */
    private function alignmentHex(string $color): string
    {
        return match ($color) {
            'yellow' => '#ca8a04',
            'orange' => '#ea580c',
            'red' => '#dc2626',
            'grey' => '#64748b',
            default => '#16a34a',
        };
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
