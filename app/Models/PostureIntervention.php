<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostureIntervention extends Model
{
    protected $fillable = [
        'posture_type', 'posture_label', 'age_group', 'category', 'program', 'level',
        'title', 'description', 'sets_reps', 'frequency', 'days',
        'duration_minutes', 'image_path', 'image_url', 'order_index',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order_index' => 'integer',
        'days' => 'array',
    ];

    public const WEEKDAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    /**
     * Weekdays for an item that has none set, read from its frequency text
     * ("Daily", "3x per week", ...). Programs start on different days so a
     * patient's week is spread out instead of stacked on Monday.
     *
     * @return array<int, string>
     */
    public static function defaultDays(?string $frequency, ?string $program = null): array
    {
        $text = strtolower((string) $frequency);

        if (str_contains($text, 'daily') || str_contains($text, 'every day')) {
            return self::WEEKDAYS;
        }

        $perWeek = preg_match('/(\d+)\s*x/', $text, $m) ? (int) $m[1] : (str_contains($text, 'week') ? 1 : 3);

        $patterns = [
            1 => ['saturday'],
            2 => ['tuesday', 'friday'],
            3 => ['monday', 'wednesday', 'friday'],
            4 => ['monday', 'tuesday', 'thursday', 'friday'],
            5 => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            6 => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
        ];
        $days = $patterns[min(max($perWeek, 1), 6)] ?? self::WEEKDAYS;

        // Massage sits on the days between pool sessions.
        if ($program === 'massage_therapy' && $perWeek === 3) {
            return ['tuesday', 'thursday', 'saturday'];
        }

        return $perWeek >= 7 ? self::WEEKDAYS : $days;
    }

    /**
     * The weekdays this item runs on, falling back to its frequency.
     *
     * @return array<int, string>
     */
    public function scheduledDays(): array
    {
        $days = array_values(array_intersect(self::WEEKDAYS, $this->days ?? []));

        return $days ?: self::defaultDays($this->frequency, $this->program);
    }

    protected $appends = ['image_src'];

    /**
     * The image the app should render: the external link wins, otherwise the
     * uploaded file on the public disk. Null when neither is set.
     */
    public function getImageSrcAttribute(): ?string
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }

        if (empty($this->image_path)) {
            return null;
        }

        $relative = '/storage/' . ltrim($this->image_path, '/');

        // `Storage::url()` prefixes APP_URL, which is normally a local-only
        // domain such as http://backend.test that a phone on the LAN cannot
        // resolve. Building from the current request keeps the image on the
        // same host the app already reached the API on.
        $root = request()->root();

        return $root !== '' ? rtrim($root, '/') . $relative : url($relative);
    }

    public function scopeProgram(Builder $query, string $program): Builder
    {
        return $query->where('program', $program);
    }
}
