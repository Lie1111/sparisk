<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostureSeverityBand extends Model
{
    protected $fillable = [
        'level', 'min', 'max', 'label', 'color',
    ];

    protected $casts = [
        'min' => 'decimal:2',
        'max' => 'decimal:2',
    ];

    /**
     * Ordered band definitions (with ids) from the database, falling back to the
     * locked config/sparisk.php definitions when the table is empty.
     *
     * Keeps the assessment engines and the mobile API reading from the same
     * source that the admin editor writes to.
     */
    public static function orderedConfig(): array
    {
        $bands = self::orderBy('min')->get();

        if ($bands->isEmpty()) {
            return config('sparisk.severity_bands');
        }

        return $bands->map(fn (self $band) => [
            'id' => $band->id,
            'min' => (float) $band->min,
            'max' => $band->max === null ? null : (float) $band->max,
            'level' => $band->level,
            'label' => $band->label,
            'color' => $band->color,
        ])->all();
    }
}
