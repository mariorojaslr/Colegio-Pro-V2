<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthicsSanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'collegiate_id',
        'type', // temporary, permanent
        'reason',
        'arguments',
        'start_date',
        'end_date',
        'status', // active, lifted, expired
        'lifted_at',
        'lifted_reason',
        'lifted_by',
        'approved_by_president',
    ];

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class);
    }

    public function lifter()
    {
        return $this->belongsTo(User::class, 'lifted_by');
    }

    public function votes()
    {
        return $this->hasMany(EthicsCommissionVote::class);
    }
}
