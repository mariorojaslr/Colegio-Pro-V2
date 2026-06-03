<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderItem extends Model
{
    use HasFactory;

    protected $fillable = ['slider_id', 'image_url', 'title', 'description', 'link', 'order'];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}
