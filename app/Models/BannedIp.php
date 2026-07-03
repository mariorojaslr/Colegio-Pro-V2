<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedIp extends Model
{
    protected $table = 'banned_ips';
    protected $fillable = ['ip_address', 'reason', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
