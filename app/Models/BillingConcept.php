<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingConcept extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'default_amount',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function collegiateDues()
    {
        return $this->hasMany(CollegiateDue::class);
    }
}
