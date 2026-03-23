<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'school_id', 'name', 'description', 'price', 'image', 'is_available'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
