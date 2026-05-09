<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $recentQuizzes = QuizSession::where('user_id', $userId)
            ->where('predicted_performance', '!=', 'In Progress')
            ->latest()
            ->get()
            ->map(function($session) {
                $firstAnswer = QuizAnswer::where('quiz_session_id', $session->id)->first();
                $quizDisplayName = "General Quiz";

                if ($firstAnswer && $firstAnswer->question) {
                    $quizDisplayName = "Quiz #" . $firstAnswer->question->batch_number;
                }

                return (object) [
                    'quiz_name'  => $quizDisplayName,
                    'prediction' => $session->predicted_performance,
                    'created_at' => $session->created_at,
                ];
            });

        $completedCount = $recentQuizzes->count();

        $latestSession = QuizSession::where('user_id', $userId)
            ->where('predicted_performance', '!=', 'In Progress')
            ->latest()
            ->first();

        $grade = $latestSession ? $latestSession->predicted_performance : 'No Data';

        $avgScore = 0;
        if ($completedCount > 0) {
            $total = $recentQuizzes->sum(function($q) {
                return match($q->prediction) {
                    'Excellent' => 95,
                    'Good'      => 80,
                    'Pass'      => 75,
                    'Fail'      => 30,
                    default     => 0,
                };
            });
            $avgScore = round($total / $completedCount);
        }

        // بيانات الرسم البياني
        $labels = $recentQuizzes->map(fn($q) => $q->created_at->format('M d'))->toArray();
        $scores = $recentQuizzes->map(function($q) {
            return match($q->prediction) {
                'Excellent' => 95,
                'Good'      => 80,
                'Pass'      => 75,
                'Fail'      => 30,
                default     => 0,
            };
        })->toArray();

        return view('grades.index', compact(
            'recentQuizzes', 'completedCount', 'grade', 'avgScore', 'labels', 'scores'
        ));
    }
}