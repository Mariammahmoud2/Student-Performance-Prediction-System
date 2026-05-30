<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // eager load عشان نتجنب N+1
        $sessions = QuizSession::with(['answers.question'])
            ->where('user_id', $userId)
            ->completed()
            ->latest()
            ->get();

        $recentQuizzes = $sessions->map(function ($session) {
            $firstAnswer     = $session->answers->first();
            $quizDisplayName = $firstAnswer?->question
                ? 'Quiz #' . $firstAnswer->question->batch_number
                : 'General Quiz';

            return (object) [
                'quiz_name'  => $quizDisplayName,
                'prediction' => $session->predicted_performance,
                'created_at' => $session->created_at,
            ];
        });

        $completedCount = $sessions->count();
        $grade = $sessions->first()?->predicted_performance ?? 'No Data';

        $avgScore = 0;
        if ($completedCount > 0) {
            $total = $sessions->sum(fn($s) => $this->predictionToScore($s->predicted_performance));
            $avgScore = round($total / $completedCount);
        }

        $labels = $recentQuizzes->map(fn($q) => $q->created_at->format('M d'))->toArray();
        $scores = $sessions->map(fn($s) => $this->predictionToScore($s->predicted_performance))->toArray();

        return view('grades.index', compact(
            'recentQuizzes', 'completedCount', 'grade', 'avgScore', 'labels', 'scores'
        ));
    }

    private function predictionToScore(string $prediction): int
    {
        return match($prediction) {
            'Excellent' => 95,
            'Good'      => 80,
            'Pass'      => 75,
            'Fail'      => 30,
            default     => 0,
        };
    }
}