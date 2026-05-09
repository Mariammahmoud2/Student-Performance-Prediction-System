<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question_text', 'input_type', 'options', 'batch_number', 'min', 'max'];

     protected $casts = [
        'options' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}