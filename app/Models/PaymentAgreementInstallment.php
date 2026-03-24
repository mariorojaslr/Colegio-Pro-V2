<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAgreementInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_agreement_id',
        'due_date',
        'amount',
        'status', // pending, paid
        'paid_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function agreement()
    {
        return $this->belongsTo(PaymentAgreement::class, 'payment_agreement_id');
    }
}
