<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A weak / tight / severe highlight over one muscle on the muscle body image.
 * x / y / width / height are the normalized (0..1) box of the shape and
 * rotation is in degrees. `points` is the muscle outline inside that box
 * (-0.5..0.5 on each axis, before rotation); without points the highlight is
 * drawn as a soft ellipse.
 */
class MuscleHighlight extends Model
{
    protected $fillable = [
        'muscle_condition_id', 'gender', 'view', 'muscle', 'status',
        'x', 'y', 'width', 'height', 'rotation', 'points', 'order_index',
    ];

    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'width' => 'float',
        'height' => 'float',
        'rotation' => 'integer',
        'points' => 'array',
        'order_index' => 'integer',
    ];

    public function condition(): BelongsTo
    {
        return $this->belongsTo(MuscleCondition::class, 'muscle_condition_id');
    }
}
