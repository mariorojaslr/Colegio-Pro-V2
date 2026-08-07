<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collegiate extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
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
        'birth_date',
        'address',
        'city',
        'plus_code',
        'latitude',
        'longitude',
        'degree',
        'workplaces_info',
        'practicing_since_year',
        'billing_profile',
        'historical_debt',
        'historical_debt_months',
        'observations',
        'is_exempt_from_fees',
    ];

    protected $casts = [
        'is_ethics_compliant' => 'boolean',
        'is_fees_compliant' => 'boolean',
        'is_fully_documented' => 'boolean',
        'is_exempt_from_fees' => 'boolean',
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
        return $this->is_fees_compliant && $this->is_fully_documented && !$this->isSanctioned();
    }

    /**
     * Actualiza el estado de cumplimiento del colegiado, incluyendo la regla
     * de suspensión automática por 3 meses de mora.
     */
    public function updateComplianceStatus()
    {
        // 1. Ética: Está al día si no tiene sanciones GRAVES activas.
        $hasGraveSanction = $this->sanctions()->where('status', 'active')->where('severity', 'grave')->exists();
        $this->is_ethics_compliant = !$hasGraveSanction;

        // 2. Financiero y Suspensión Automática:
        // Contamos las cuotas pendientes o vencidas
        $unpaidDuesCount = $this->dues()->whereIn('status', ['pending', 'overdue'])->count();
        $this->is_fees_compliant = ($unpaidDuesCount === 0);

        // Regla: Si debe 3 o más cuotas, se suspende automáticamente.
        if ($unpaidDuesCount >= 3) {
            $this->professional_situation = 'Suspendido por Mora';
        } else if ($this->professional_situation === 'Suspendido por Mora' && $unpaidDuesCount < 3) {
            // Si pagó y bajó de 3, lo restauramos a Activo.
            $this->professional_situation = 'Activo';
        }

        // Si tiene una sanción grave, forzamos su situación a Suspendido por Ética
        if ($hasGraveSanction) {
            $this->professional_situation = 'Suspendido por Ética';
        }

        $this->save();
    }

    /**
     * Perfilado Progresivo: Devuelve la siguiente tarea faltante (una sola)
     * para no agobiar al colegiado.
     */
    public function getNextOnboardingTask()
    {
        // 1. Prioridad: Foto de perfil
        if (empty($this->avatar_url)) {
            return [
                'type' => 'avatar',
                'title' => 'Sube tu Foto de Perfil',
                'description' => 'Ayúdanos a reconocerte. Sube una foto tuya tipo carnet, de frente y clara.',
                'icon' => 'bi-camera'
            ];
        }

        // 2. Prioridad: Fecha de Nacimiento
        if (empty($this->birth_date)) {
            return [
                'type' => 'birth_date',
                'title' => 'Fecha de Nacimiento Faltante',
                'description' => 'Necesitamos tu fecha de nacimiento para completar tus datos filiatorios.',
                'icon' => 'bi-calendar-event'
            ];
        }

        // 3. Prioridad: Dirección
        if (empty($this->address)) {
            return [
                'type' => 'address',
                'title' => 'Domicilio Particular Faltante',
                'description' => 'Registra tu dirección de residencia actual para notificaciones formales.',
                'icon' => 'bi-geo-alt'
            ];
        }

        // 4. Prioridad: Lugar de trabajo
        if (empty($this->workplaces_info)) {
            return [
                'type' => 'workplaces_info',
                'title' => 'Lugar de Trabajo Faltante',
                'description' => 'Indica en qué institución, consultorio o clínica ejerces la profesión actualmente.',
                'icon' => 'bi-building'
            ];
        }

        // 5. Prioridad: Documentos obligatorios
        $school = $this->school;
        if ($school) {
            // Check mandatory requirements without approved documents
            $pendingReq = ComplianceRequirement::where('school_id', $school->id)
                ->where('is_mandatory', true)
                ->whereDoesntHave('documents', function($q) {
                    $q->where('collegiate_id', $this->id)
                      ->where('status', 'approved');
                })
                ->first();

            if ($pendingReq) {
                return [
                    'type' => 'document',
                    'title' => 'Documento Pendiente: ' . $pendingReq->name,
                    'description' => 'Te falta subir este requisito obligatorio para tener tu legajo digital completo.',
                    'icon' => 'bi-file-earmark-text',
                    'route' => route('compliance.index')
                ];
            }
        }

        return null;
    }
}
