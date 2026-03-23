<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'icon',
        'is_active',
        'base_price',
        'is_seasonal',
        'seasonal_price',
        'season_range',
        'has_calendar',
        'capacity',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_seasonal' => 'boolean',
        'has_calendar' => 'boolean',
        'base_price' => 'decimal:2',
        'seasonal_price' => 'decimal:2',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Determina el precio actual según la lógica de temporada (opcional).
     */
    public function getCurrentPrice()
    {
        if ($this->is_seasonal && $this->seasonal_price) {
            // Aquí iría lógica de fechas, por ahora retornamos el estacional si está activo el flag
            return $this->seasonal_price;
        }
        return $this->base_price;
    }
}
