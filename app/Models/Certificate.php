<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'exam_id', 'code', 'issued_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
