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

    protected $appends = ['status_label', 'regions'];

    /**
     * The measurement regions that grade this muscle, so the app can colour it
     * from the stored findings instead of one band for the whole photo.
     *
     * @return array<int, string>
     */
    public function getRegionsAttribute(): array
    {
        $map = config("sparisk.marker_regions.{$this->view}", []);

        return array_values($map[$this->label] ?? []);
    }

    /**
     * The single label this marker prints — the same word on the patient body
     * view, in the marker detail sheet and in the admin list. The highlight
     * colour carries the severity band, so the label names the muscle state.
     */
    public function getStatusLabelAttribute(): string
    {
        $severity = strtolower((string) $this->severity);
        $type = strtolower((string) $this->type);

        $labels = config("sparisk.marker_status_labels.{$severity}");

        if (is_array($labels) && isset($labels[$type])) {
            return $labels[$type];
        }

        return ucfirst($severity !== '' ? $severity : 'normal');
    }

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
                'status_label' => $marker->status_label,
                'regions' => $marker->regions,
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
