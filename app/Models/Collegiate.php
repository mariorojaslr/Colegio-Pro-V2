<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collegiate extends Model
{
    protected $fillable = [
        'school_id',
        'registration_number',
        'first_name',
        'last_name',
        'email',
        'dni',
        'phone',
        'status',
        'avatar_url',
        'is_ethics_compliant',
        'ethics_expiry',
        'is_fees_compliant',
        'fees_expiry',
        'is_fully_documented',
        'compliance_notes',
        'custom_attributes',
    ];

    protected $casts = [
        'is_ethics_compliant' => 'boolean',
        'is_fees_compliant' => 'boolean',
        'is_fully_documented' => 'boolean',
        'ethics_expiry' => 'date',
        'fees_expiry' => 'date',
        'custom_attributes' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dues()
    {
        return $this->hasMany(CollegiateDue::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingDues()
    {
        return $this->hasMany(CollegiateDue::class)->whereIn('status', ['pending', 'overdue']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sanctions()
    {
        return $this->hasMany(EthicsSanction::class);
    }

    /**
     * @return bool
     */
    public function isSanctioned()
    {
        return $this->sanctions()->where('status', 'active')->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(CollegiateDocument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentAgreements()
    {
        return $this->hasMany(PaymentAgreement::class);
    }

    /**
     * Verifica si el colegiado está plenamente habilitado para emitir certificados.
     * Cruza datos de ética (sanciones), aportes y legajo digital.
     */
    public function isEnabledForCertificates()
    {
        return $this->is_ethics_compliant && 
               $this->is_fees_compliant && 
               $this->is_fully_documented &&
               !$this->isSanctioned();
    }
}
