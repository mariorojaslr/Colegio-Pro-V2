<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'slug', 'price', 'interval', 'max_users', 'max_storage', 'features'];

    protected $casts = [
        'features' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Retorna el precio para ser mostrado en la landing page.
     */
    public function getDisplayPrice()
    {
        return $this->price;
    }

    /**
     * Verifica si el plan incluye un feature específico (ej. streaming, api_access).
     */
    public function hasFeature(string $featureKey): bool
    {
        if (!is_array($this->features)) return false;
        
        return in_array($featureKey, $this->features) || 
               (isset($this->features[$featureKey]) && $this->features[$featureKey] === true);
    }

    /**
     * Verifica si el plan tiene acceso a Bunny Stream.
     */
    public function hasStreaming(): bool
    {
        return $this->hasFeature('bunny_stream');
    }
}
