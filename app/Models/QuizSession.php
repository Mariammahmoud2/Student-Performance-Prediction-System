<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class QuizSession extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'predicted_performance',
        'model_analysis',
    ];

    // ── Scopes ──────────────────────────────────────
    public function scopeInProgress($query)
    {
        return $query->where('predicted_performance', 'In Progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('predicted_performance', '!=', 'In Progress')
                     ->where('predicted_performance', '!=', 'Abandoned');
    }

    // ── Relations ───────────────────────────────────
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}