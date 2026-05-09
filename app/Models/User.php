<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; // تأكدي من وجود ده
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
     use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function quizSessions()
{
    return $this->hasMany(QuizSession::class);
}

public function answers()
{
    return $this->hasMany(QuizAnswer::class);
}

public function quizResults()
{
    return $this->hasMany(UserQuiz::class);
}
 public function quizzes(): HasMany
    {
        // نستخدم QuizSession لأنه الموديل الذي يحتوي على predicted_performance
        return $this->hasMany(QuizSession::class); 
    }
}
