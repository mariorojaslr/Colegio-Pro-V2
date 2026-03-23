<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthicsCommissionVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ethics_sanction_id',
        'user_id',
        'vote', // approved, rejected
        'comment',
    ];

    public function sanction()
    {
        return $this->belongsTo(EthicsSanction::class, 'ethics_sanction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
