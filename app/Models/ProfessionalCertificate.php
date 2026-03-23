<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProfessionalCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'collegiate_id',
        'certificate_type_id',
        'code',
        'uuid',
        'issued_at',
        'expires_at',
        'status', // active, expired, revoked
        'pdf_url',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->code = 'CERT-' . strtoupper(Str::random(10));
        });
    }

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }

    public function type()
    {
        return $this->belongsTo(CertificateType::class, 'certificate_type_id');
    }
}
