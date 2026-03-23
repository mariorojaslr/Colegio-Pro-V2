<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'amount',
        'effective_date',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
