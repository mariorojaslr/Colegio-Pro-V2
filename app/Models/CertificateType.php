<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'price',
        'validity_days',
        'is_single_use',
        'requires_clearance',
        'requires_no_sanctions',
        'is_active',
        'template_content',
        'has_qr',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
