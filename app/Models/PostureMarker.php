<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A configurable red / green marker drawn on a body character image.
 *
 * Coordinates are normalized (0..1) relative to the character image, so the
 * same marker works at any render size in the mobile app. `type` is one of:
 *   - tight  (red)    overactive / shortened muscle
 *   - weak   (green)  underactive / lengthened muscle
 *   - normal (neutral)
 */
class PostureMarker extends Model
{
    protected $fillable = [
        'view', 'gender', 'severity', 'type', 'color', 'label', 'muscle',
        'x', 'y', 'width', 'height',
        'condition', 'order_index', 'is_active',
    ];

    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'width' => 'float',
        'height' => 'float',
        'order_index' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Ordered markers, grouped by view, for the mobile app / editor.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function groupedByView(
        ?string $gender = null,
        ?string $condition = null,
        ?string $severity = null,
    ): array {
        $query = self::where('is_active', true);

        if ($gender !== null) {
            $query->whereIn('gender', ['all', $gender]);
        }
        if ($condition !== null) {
            // Condition-specific sets (SATA Figures) are independent of the
            // generic severity sets.
            $query->where('condition', $condition);
        } else {
            $query->whereNull('condition');
            if ($severity !== null) {
                $query->where('severity', $severity);
            }
        }

        $markers = $query->orderBy('order_index')->orderBy('id')->get();

        $grouped = [];
        foreach ($markers as $marker) {
            $grouped[$marker->view][] = [
                'id' => $marker->id,
                'view' => $marker->view,
                'gender' => $marker->gender,
                'severity' => $marker->severity,
                'type' => $marker->type,
                'color' => $marker->color,
                'label' => $marker->label,
                'muscle' => $marker->muscle,
                'x' => $marker->x,
                'y' => $marker->y,
                'width' => $marker->width,
                'height' => $marker->height,
                'condition' => $marker->condition,
                'order_index' => $marker->order_index,
            ];
        }

        return $grouped;
    }
}
