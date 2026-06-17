<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'collegiate_id',
        'department',
        'role',
        'name',
        'image_path',
        'is_substitute',
        'order'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function collegiate()
    {
        return $this->belongsTo(Collegiate::class, 'collegiate_id');
    }
}
