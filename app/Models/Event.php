<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'school_id', 'title', 'description', 'date', 'location', 'capacity'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
