<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class QuizViewController extends Controller
{
    /**
     * عرض لوحة التحكم وإحصائيات الطالب
     */
    public function dashboard()
    {
        $userId = auth()->id();

        $completedCount = QuizSession::where('user_id', $userId)
            ->where('predicted_performance', '!=', 'In Progress')
            ->count();

        $latestSession = QuizSession::where('user_id', $userId)
            ->where('predicted_performance', '!=', 'In Progress')
            ->latest()
            ->first();

        $grade = $latestSession ? $latestSession->predicted_performance : 'No Data';

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

                $score = match($session->predicted_performance) {
                    'Excellent' => 95,
                    'Good'      => 80,
                    'Pass'      => 75,
                    'Fail'      => 30,
                    default     => 0,
                };

                return (object) [
                    'quiz_name'  => $quizDisplayName,
                    'prediction' => $session->predicted_performance,
                    'created_at' => $session->created_at,
                    'score'      => $score,
                ];
            });

        $avgScore = $completedCount > 0
            ? round($recentQuizzes->avg('score'))
            : 0;

        $highestQuiz  = $recentQuizzes->sortByDesc('score')->first();
        $highestScore = $highestQuiz ? $highestQuiz->score : 0;
        $highestName  = $highestQuiz ? $highestQuiz->quiz_name : '-';

        $lowestQuiz  = $recentQuizzes->sortBy('score')->first();
        $lowestScore = $lowestQuiz ? $lowestQuiz->score : 0;

        return view('dashboard', compact(
            'completedCount', 'avgScore', 'grade', 'recentQuizzes',
            'highestScore', 'highestName', 'lowestScore'
        ));
    }

    /**
     * عرض قائمة الاختبارات المتاحة
     */
    public function index()
    {
        $batches = Question::select('batch_number')
            ->distinct()
            ->orderBy('batch_number')
            ->get();

        return view('quizzes.index', compact('batches'));
    }

    /**
     * عرض صفحة السؤال (البداية أو التنقل)
     */
    public function show($batch)
    {
        $questions = Question::where('batch_number', $batch)->paginate(1);

        $session = QuizSession::firstOrCreate(
            ['user_id' => auth()->id(), 'predicted_performance' => 'In Progress']
        );

        return view('quizzes.show', [
            'questions' => $questions,
            'batch'     => $batch,
            'sessionId' => $session->id
        ]);
    }

    /**
     * حفظ الإجابة ومعالجة النتيجة النهائية عبر AI
     */
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'question_id'     => 'required|exists:questions,id',
            'answer'          => 'required',
            'batch'           => 'required|integer',
            'next_page'       => 'required|integer',
            'quiz_session_id' => 'required|exists:quiz_sessions,id',
        ]);

        $session = QuizSession::where('id', $request->quiz_session_id)
                               ->where('user_id', auth()->id())
                               ->firstOrFail();

        QuizAnswer::updateOrCreate(
            [
                'user_id'         => auth()->id(),
                'question_id'     => $request->question_id,
                'quiz_session_id' => $session->id,
            ],
            ['answer' => $request->answer]
        );

        $totalQuestions = Question::where('batch_number', $request->batch)->count();

        if ($request->next_page <= $totalQuestions) {
            return redirect()->to(route('quizzes.show', $request->batch) . '?page=' . $request->next_page);
        }

        // --- نهاية الاختبار: معالجة النتائج ---
        $allAnswers = QuizAnswer::where('quiz_session_id', $session->id)
            ->orderBy('question_id', 'asc')
            ->pluck('answer')
            ->toArray();

        // 1. التوقع (Predict)
        $predictProcess = Process::input(json_encode(['answers' => $allAnswers]))
            ->run('python3 ' . base_path('scripts/predict_student.py'));

        if ($predictProcess->successful()) {
            $output = json_decode($predictProcess->output(), true);
            $prediction = $output['prediction'] ?? 'N/A';
            $session->update(['predicted_performance' => $prediction]);

            // 2. إذا فشل الطالب، اجلب التوصيات عبر سكريبت المقارنة
            if ($prediction === 'Fail') {
                $studentAnswersMap = QuizAnswer::where('quiz_session_id', $session->id)
                    ->with('question')
                    ->get()
                    ->pluck('answer', 'question.question_text')
                    ->toArray();

                $recommendations = $this->getRecommendationsFromPython($studentAnswersMap);

                return view('quizzes.recommendations', [
                    'session' => $session,
                    'recommendations' => $recommendations,
                    'prediction' => $prediction,
                ]);
            }

            return view('quizzes.result', compact('prediction', 'session'));
        }

        return redirect()->route('quizzes.index')->with('error', 'AI Processing Error');
    }

    /**
     * استدعاء بايثون لجلب التوصيات (المقارنة بملف الـ pkl)
     */
    private function getRecommendationsFromPython($studentAnswersMap)
    {
        try {
            $process = Process::input(json_encode(['student_answers' => $studentAnswersMap]))
                ->run('python3 ' . base_path('scripts/compare_answers.py'));

            if ($process->successful()) {
                $output = json_decode($process->output(), true);
                return $output['recommendations'] ?? [];
            }
            
            Log::error('Python Compare Error: ' . $process->errorOutput());
            return [];
        } catch (\Exception $e) {
            Log::error('Recommendations Exception: ' . $e->getMessage());
            return [];
        }
    }
}