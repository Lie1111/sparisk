<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostureMarker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Serves the configurable posture body markers to the mobile app.
 *
 * Markers are grouped by view and returned with the character image paths so
 * the app can render the red (tight) / green (weak) overlay.
 */
class PostureMarkerController extends Controller
{
    private const CHARACTER_IMAGES = [
        'male' => [
            'front' => 'storage/character/men-front.png',
            'back' => 'storage/character/men-back.png',
            'right_side' => 'storage/character/men-right.png',
            'left_side' => 'storage/character/men-left.png',
        ],
        'female' => [
            'front' => 'storage/character/women-front.png',
            'back' => 'storage/character/women-back.png',
            'right_side' => 'storage/character/women-right.png',
            'left_side' => 'storage/character/women-left.png',
        ],
    ];

    public function index(Request $request): JsonResponse
    {
        $gender = $request->query('gender');
        if (!in_array($gender, ['male', 'female'], true)) {
            $gender = null;
        }

        $condition = $request->filled('condition') ? (string) $request->query('condition') : null;

        $severity = $request->query('severity');
        if (!in_array($severity, ['normal', 'moderate', 'severe'], true)) {
            $severity = null;
        }

        return response()->json([
            'views' => ['front', 'back', 'right_side', 'left_side'],
            'character_images' => self::CHARACTER_IMAGES,
            'markers' => PostureMarker::groupedByView($gender, $condition, $severity),
        ]);
    }
}
