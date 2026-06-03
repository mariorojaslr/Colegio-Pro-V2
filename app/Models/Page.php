<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'title', 'slug', 'content', 'is_published'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
