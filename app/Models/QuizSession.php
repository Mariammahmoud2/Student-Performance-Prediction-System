<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // مهم جداً للـ UUID

class QuizSession extends Model
{
    use HasUuids; 

  protected $fillable = [
    'user_id', 
    'score', // <--- أضيفي هذا
    'predicted_performance', 
    'model_analysis'
];
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
