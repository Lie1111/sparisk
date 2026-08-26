<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostureSetting;
use Illuminate\Http\JsonResponse;

class PostureSettingController extends Controller
{
    /**
     * Expose the SATA Age-Based Posture Reference to the mobile app.
     *
     * Settings are grouped by view/section with a reference value per age
     * group, alongside the age-group definitions and the fixed severity bands
     * used to interpret deviation (ABS(Clinical Angle - Age Reference)).
     */
    public function index(): JsonResponse
    {
        $settings = PostureSetting::orderByRaw("FIELD(view, 'front', 'back', 'right_side', 'left_side')")
            ->orderBy('section')
            ->orderBy('age_group')
            ->get();

        $grouped = $settings->groupBy(fn (PostureSetting $setting) => $setting->view . '.' . $setting->section)
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'view' => $first->view,
                    'section' => $first->section,
                    'label' => $first->label,
                    'interpretation' => $first->interpretation,
                    'references' => $rows->mapWithKeys(fn (PostureSetting $setting) => [
                        $setting->age_group => (float) $setting->reference_value,
                    ]),
                ];
            })
            ->values();

        return response()->json([
            'age_groups' => config('sparisk.age_groups'),
            'severity_bands' => config('sparisk.severity_bands'),
            'settings' => $grouped,
        ]);
    }
}
