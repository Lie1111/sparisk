<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academy extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'address', 'state', 'phone', 'email', 'logo_path'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(AcademyMember::class);
    }

    public function patients()
    {
        return $this->hasManyThrough(
            Patient::class,
            AcademyMember::class,
            'academy_id',
            'id',
            'id',
            'patient_id'
        );
    }
}
