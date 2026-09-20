<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostureIntervention extends Model
{
    protected $fillable = [
        'posture_type', 'posture_label', 'age_group', 'category', 'program', 'level',
        'title', 'description', 'sets_reps', 'frequency',
        'duration_minutes', 'image_path', 'image_url', 'order_index',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order_index' => 'integer',
    ];

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
