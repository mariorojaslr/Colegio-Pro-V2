<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegiateDue extends Model
{
    use HasFactory;

    protected $fillable = [
        'collegiate_id',
        'amount',
        'due_date',
        'concept',
        'due_type',
        'status', // pending, paid, overdue, defaulted
        'paid_at',
        'payment_reference',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }
}
