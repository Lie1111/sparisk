<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostureSetting extends Model
{
    protected $fillable = [
        'view', 'section', 'label', 'age_group',
        'reference_value', 'interpretation'
    ];

    protected $casts = [
        'reference_value' => 'decimal:2',
    ];
}
