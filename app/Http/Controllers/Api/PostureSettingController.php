<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostureSeverityBand;
use App\Models\PostureSetting;
use App\Services\Sparisk\SpariskMeasurementEngine;
use Illuminate\Http\JsonResponse;

class PostureSettingController extends Controller
{
    public function __construct(private SpariskMeasurementEngine $measurementEngine) {}

    /**
     * Expose the SATA Age-Based Posture Reference to the mobile app.
     *
     * Settings are grouped by view/section with a reference value per age
     * group, alongside the age-group definitions and the fixed severity bands
     * used to interpret deviation (ABS(Clinical Angle - Age Reference)).
     *
     * The response is the app's ONLY source for the reference values, the
     * On Point / Near Point / Off Point thresholds and the measurement
     * definitions. The app never hardcodes a copy of any of them.
     */
    public function index(): JsonResponse
    {
        $viewOrder = array_flip(['front', 'back', 'right_side', 'left_side']);

        // Sorted in PHP because FIELD() is MySQL-only: the API must return the
        // same order on the SQLite test database (and any other driver).
        $settings = PostureSetting::orderBy('section')->orderBy('age_group')->get()
            ->sortBy(fn (PostureSetting $setting) => sprintf(
                '%d-%s-%s',
                $viewOrder[$setting->view] ?? 99,
                $setting->section,
                $setting->age_group,
            ))
            ->values();

        $grouped = $settings->groupBy(fn (PostureSetting $setting) => $setting->view . '.' . $setting->section)
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'view' => $first->view,
                    'section' => $first->section,
                    'label' => $first->label,
                    'interpretation' => $first->interpretation,
                    'definition' => $this->measurementEngine->definitionFor((string) $first->section),
                    'references' => $rows->mapWithKeys(fn (PostureSetting $setting) => [
                        $setting->age_group => (float) $setting->reference_value,
                    ]),
                    'tolerances' => $rows->mapWithKeys(fn (PostureSetting $setting) => [
                        $setting->age_group => $setting->tolerance(),
                    ]),
                ];
            })
            ->values();

        return response()->json([
            'age_groups' => config('sparisk.age_groups'),
            'severity_bands' => PostureSeverityBand::orderedConfig(),
            // The shared point-band rule: one config, one wording, used by the
            // app, the web portal and the PDF report.
            'deviation_bands' => config('sparisk.deviation_bands'),
            'deviation_rule' => $this->measurementEngine->bandsRuleText(),
            'output_statuses' => config('sparisk.posture_output_statuses'),
            'measurement_definitions' => config('sparisk.measurement_definitions'),
            'settings' => $grouped,
        ]);
    }
}
