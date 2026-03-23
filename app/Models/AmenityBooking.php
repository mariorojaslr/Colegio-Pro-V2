<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityBooking extends Model
{
    protected $fillable = [
        'amenity_id',
        'collegiate_id',
        'booking_date',
        'slot_time',
        'price_paid',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price_paid' => 'decimal:2',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }
}
