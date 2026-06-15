<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EthicsInfractionType extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'severity',
        'duration_months',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_months' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
