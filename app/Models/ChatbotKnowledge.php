<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledge extends Model
{
    protected $table = 'chatbot_knowledge';
    protected $fillable = ['school_id', 'question', 'keywords', 'answer', 'status'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
