<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EthicsRule extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'penalty_type',
        'penalty_days',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
