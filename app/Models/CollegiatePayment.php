<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegiatePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'collegiate_id',
        'amount',
        'gateway',
        'gateway_payment_id',
        'external_reference',
        'payment_method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }

    public function dues()
    {
        return $this->hasMany(CollegiateDue::class);
    }
}
