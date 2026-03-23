<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'price_international',
        'currency_international',
        'interval',
        'max_users',
        'max_storage',
        'max_traffic',
        'max_files',
        'max_images',
        'max_streaming',
        'is_one_time',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    /**
     * Determines the price of the plan based on the user's current location detected by IP.
     */
    public function getDisplayPrice()
    {
        $locationService = app(\App\Services\LocationService::class);
        
        if ($locationService->isFromArgentina()) {
            return $this->price;
        }

        return $this->price_international;
    }

    /**
     * Determines the display currency of the plan based on the user's location.
     */
    public function getDisplayCurrencySymbol()
    {
        $locationService = app(\App\Services\LocationService::class);
        
        if ($locationService->isFromArgentina()) {
            return '$'; // Pesos Argentinos
        }

        return $this->currency_international === 'EUR' ? '€' : 'USD $';
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
