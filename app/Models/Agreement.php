<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'logo_url',
        'description',
        'discount_percentage',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
