<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostureIntervention extends Model
{
    protected $fillable = [
        'posture_type', 'posture_label', 'age_group', 'category',
        'title', 'description', 'sets_reps', 'frequency',
        'duration_minutes', 'order_index',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order_index' => 'integer',
    ];
}
