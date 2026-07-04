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
        'background_path',
        'page_size',
        'page_orientation',
        'design_settings',
    ];

    protected $casts = [
        'design_settings' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function signatories()
    {
        return $this->belongsToMany(BoardMember::class, 'certificate_type_board_member', 'certificate_type_id', 'board_member_id');
    }
}
