<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory;

     protected $fillable = ['user_id', 'question_id', 'quiz_session_id', 'answer'];

    // علاقة مع المستخدم (الطالب)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع السؤال
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // علاقة مع الجلسة (اختياري لو بتستخدمي الـ API)
    public function session()
    {
        return $this->belongsTo(QuizSession::class, 'quiz_session_id');
    }
}