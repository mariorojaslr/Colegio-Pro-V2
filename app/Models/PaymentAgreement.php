<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'collegiate_id',
        'type',
        'total_amount_original',
        'total_amount_agreement',
        'installment_count',
        'status',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'total_amount_original' => 'decimal:2',
        'total_amount_agreement' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }

    public function installments()
    {
        return $this->hasMany(PaymentAgreementInstallment::class);
    }
}
