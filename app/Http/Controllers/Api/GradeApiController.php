<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;

class GradeApiController extends Controller
{
    public function index()
    {
        $sessions = QuizSession::with(['answers.question'])
            ->where('user_id', auth()->id())
            ->completed()
            ->latest()
            ->get();

        $recentQuizzes = $sessions->map(function ($session) {
            $firstAnswer = $session->answers->first();
            return [
                'quiz_name'  => $firstAnswer?->question
                    ? 'Quiz #' . $firstAnswer->question->batch_number
                    : 'General Quiz',
                'prediction' => $session->predicted_performance,
                'score'      => $this->predictionToScore($session->predicted_performance),
                'created_at' => $session->created_at,
            ];
        });

        $completedCount = $sessions->count();
        $avgScore = $completedCount > 0
            ? round($recentQuizzes->avg('score'))
            : 0;

        return response()->json([
            'completed_count' => $completedCount,
            'avg_score'       => $avgScore,
            'grade'           => $sessions->first()?->predicted_performance ?? 'No Data',
            'quizzes'         => $recentQuizzes,
        ]);
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