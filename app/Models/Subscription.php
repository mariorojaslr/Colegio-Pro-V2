<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'school_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'expires_at',
        'custom_price',
        'discount_percent',
        'discount_expires_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'discount_expires_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function getFinalPriceAttribute()
    {
        // 1. Verificar si el acuerdo/bonificación expiró
        if ($this->discount_expires_at && $this->discount_expires_at->isPast()) {
            return $this->plan ? $this->plan->price : 0;
        }

        // 2. Determinar precio base (precio custom o precio del plan)
        $basePrice = $this->custom_price !== null ? $this->custom_price : ($this->plan ? $this->plan->price : 0);

        // 3. Aplicar descuento porcentual si lo hay
        if ($this->discount_percent > 0) {
            $discountAmount = $basePrice * ($this->discount_percent / 100);
            $basePrice = max(0, $basePrice - $discountAmount);
        }

        return $basePrice;
    }

    /**
     * Verifica si la suscripción está activa y no ha expirado.
     * En caso de expirar, el sistema bloqueará el acceso al Tenant.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Si tiene fecha de expiración y ya pasó, se considera inactiva (Suspendida)
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
