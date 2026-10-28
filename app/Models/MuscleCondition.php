<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A posture condition on the muscle map (Kyphosis, Flat Back, ...).
 *
 * `effects` holds one patient-facing problem per line. `detection` decides when
 * the app shows the condition for an assessment:
 *   - classifications: decision-engine classification keys (KYPHOSIS, ...)
 *   - sections: measurement conditions ({section, level[], direction?})
 */
class MuscleCondition extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'effects', 'detection', 'order_index', 'is_active',
    ];

    protected $casts = [
        'detection' => 'array',
        'order_index' => 'integer',
        'is_active' => 'boolean',
    ];

    public function highlights(): HasMany
    {
        return $this->hasMany(MuscleHighlight::class)->orderBy('order_index')->orderBy('id');
    }

    /**
     * @return array<int, string>
     */
    public function effectList(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $this->effects))));
    }
}
