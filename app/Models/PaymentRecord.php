<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    protected $fillable = [
        'school_id',
        'amount',
        'payment_method',
        'status',
        'transaction_reference',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
