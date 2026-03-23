<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'school_id',
        'category',
        'title',
        'description',
        'thumbnail_url',
        'price',
        'lecturer',
        'duration',
        'start_date',
        'benefit',
        'bunny_video_id',
        'bunny_collection_id',
        'is_published',
        'is_live',
        'live_url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'paid_amount')->withTimestamps();
    }

    /**
     * El examen final del curso.
     */
    public function exam()
    {
        return $this->hasOne(Exam::class);
    }

    /**
     * Recursos adicionales (PDF, Diapositivas, etc)
     */
    public function resources()
    {
        return $this->hasMany(LessonResource::class);
    }
}
