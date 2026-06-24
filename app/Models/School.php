<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'custom_domain', 'logo', 'logo_icon', 
        'member_singular', 'member_plural',
        'primary_color', 'secondary_color', 'tertiary_color',
        'is_active', 'storage_used', 'traffic_used', 'user_count', 'plan_category',
        'currency_code', 'currency_symbol', 'locale', 'has_academy',
        'phone',
        'email',
        'address',
        'map_embed_code',
        'plus_code',
        'latitude',
        'longitude',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'billing_day',
        'auto_billing_enabled',
        'mp_access_token',
        'mp_public_key',
        'mp_sandbox_mode'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function membershipFees()
    {
        return $this->hasMany(MembershipFee::class);
    }

    public function activeFee()
    {
        return $this->hasMany(MembershipFee::class)->where('is_active', true)->latest()->first();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(PaymentRecord::class);
    }

    public function collegiates()
    {
        return $this->hasMany(Collegiate::class);
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }

    public function complianceRequirements()
    {
        return $this->hasMany(ComplianceRequirement::class);
    }

    /**
     * Verifica si el Tenant tiene espacio disponible según su plan.
     */
    public function canUploadFile(int $sizeInBytes): bool
    {
        $plan = $this->activeSubscription?->plan;
        if (!$plan) return false;
        
        $limitInBytes = $plan->max_storage * 1024 * 1024; // MB a Bytes
        return ($this->storage_used + $sizeInBytes) <= $limitInBytes;
    }

    /**
     * Verifica si el Tenant puede añadir más usuarios según su plan.
     */
    public function canAddUser(): bool
    {
        $plan = $this->activeSubscription?->plan;
        if (!$plan) return false;
        
        return $this->user_count < $plan->max_users;
    }

    /**
     * Retorna los colores y el logo para personalizar el frontend.
     */
    public function getActiveTheme(): array
    {
        return [
            'primary' => $this->primary_color ?? '#000000',
            'secondary' => $this->secondary_color ?? '#ffffff',
            'tertiary' => $this->tertiary_color ?? '#cccccc',
            'logo' => $this->logo,
        ];
    }
}
