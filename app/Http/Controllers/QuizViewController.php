<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use App\Helpers\QuestionMapper;

class QuizViewController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        // eager load عشان نتجنب N+1
        $completedSessions = QuizSession::with(['answers.question'])
            ->where('user_id', $userId)
            ->completed()
            ->latest()
            ->get();

        $completedCount = $completedSessions->count();

        $recentQuizzes = $completedSessions->map(function ($session) {
            $firstAnswer     = $session->answers->first();
            $quizDisplayName = $firstAnswer?->question
                ? 'Quiz #' . $firstAnswer->question->batch_number
                : 'General Quiz';

            $score = $this->predictionToScore($session->predicted_performance);

            return (object) [
                'quiz_name'  => $quizDisplayName,
                'prediction' => $session->predicted_performance,
                'created_at' => $session->created_at,
                'score'      => $score,
            ];
        });

        $grade    = $completedSessions->first()?->predicted_performance ?? 'No Data';
        $avgScore = $completedCount > 0 ? round($recentQuizzes->avg('score')) : 0;

        $highestQuiz  = $recentQuizzes->sortByDesc('score')->first();
        $highestScore = $highestQuiz?->score ?? 0;
        $highestName  = $highestQuiz?->quiz_name ?? '-';

        $lowestQuiz  = $recentQuizzes->sortBy('score')->first();
        $lowestScore = $lowestQuiz?->score ?? 0;

        return view('dashboard', compact(
            'completedCount', 'avgScore', 'grade', 'recentQuizzes',
            'highestScore', 'highestName', 'lowestScore'
        ));
    }

    public function index()
    {
        $batches = Question::select('batch_number')
            ->distinct()
            ->orderBy('batch_number')
            ->get();

        return view('quizzes.index', compact('batches'));
    }

    public function start(Request $request, $batch)
    {
        // أي session ناقصة قديمة → abandon عشان اليوزر ميعلقش
        QuizSession::where('user_id', auth()->id())
            ->inProgress()
            ->update(['predicted_performance' => 'Abandoned']);

        $session = QuizSession::create([
            'user_id'               => auth()->id(),
            'predicted_performance' => 'In Progress',
        ]);

        return redirect()->to(
            route('quizzes.show', $batch) .
            '?page=1&quiz_session_id=' . $session->id
        );
    }

    public function show(Request $request, $batch)
    {
        // تحقق إن الـ session موجودة وبتاعت اليوزر ده
        $sessionId = $request->query('quiz_session_id');

        if ($sessionId) {
            $sessionExists = QuizSession::where('id', $sessionId)
                ->where('user_id', auth()->id())
                ->inProgress()
                ->exists();

            if (!$sessionExists) {
                return redirect()->route('quizzes.index')
                    ->with('error', 'Session expired or not found. Please start a new quiz.');
            }
        }

        $questions = Question::where('batch_number', $batch)->paginate(1);

        $questions->getCollection()->transform(function ($question) {
            $question->display_text = QuestionMapper::getDisplayQuestion($question->question_text);

            if ($question->input_type === 'select' && !empty($question->options)) {
                $question->mapped_options = QuestionMapper::getMappedOptions(
                    $question->question_text,
                    $question->options
                );
            }

            return $question;
        });

        return view('quizzes.show', compact('questions', 'batch', 'sessionId'));
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'question_id'     => 'required|exists:questions,id',
            'answer'          => 'required',
            'batch'           => 'required|integer',
            'next_page'       => 'required|integer',
            'quiz_session_id' => 'required|exists:quiz_sessions,id',
        ]);

        // تحقق إن الـ session بتاعت اليوزر ده ولسه In Progress
        $session = QuizSession::where('id', $request->quiz_session_id)
            ->where('user_id', auth()->id())
            ->inProgress()
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
            return redirect()->to(
                route('quizzes.show', $request->batch) .
                '?page=' . $request->next_page .
                '&quiz_session_id=' . $session->id
            );
        }

        $nextBatch       = $request->batch + 1;
        $nextBatchExists = Question::where('batch_number', $nextBatch)->exists();

        if ($nextBatchExists) {
            return redirect()->to(
                route('quizzes.show', $nextBatch) .
                '?page=1&quiz_session_id=' . $session->id
            );
        }

        // كل الـ batches خلصت → شغّل الـ model
        $allAnswers = QuizAnswer::where('quiz_session_id', $session->id)
            ->orderBy('question_id', 'asc')
            ->pluck('answer')
            ->toArray();

        $predictProcess = Process::input(json_encode(['answers' => $allAnswers]))
            ->run('python3 ' . base_path('scripts/predict_student.py'));

        if (!$predictProcess->successful()) {
            Log::error('Python Predict Error: ' . $predictProcess->errorOutput());
            return redirect()->route('quizzes.index')
                ->with('error', 'AI Processing Error. Please try again.');
        }

        $output     = json_decode($predictProcess->output(), true);
        $prediction = $output['prediction'] ?? 'N/A';
        $session->update(['predicted_performance' => $prediction]);

        if ($prediction === 'Fail') {
            $studentAnswersMap = QuizAnswer::where('quiz_session_id', $session->id)
                ->with('question')
                ->get()
                ->pluck('answer', 'question.question_text')
                ->toArray();

            $recommendations = $this->getRecommendationsFromPython($studentAnswersMap);

            return view('quizzes.recommendations', [
                'session'         => $session,
                'recommendations' => $recommendations,
                'prediction'      => $prediction,
            ]);
        }

        return view('quizzes.result', compact('prediction', 'session'));
    }

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

    // helper مشترك بين dashboard و GradeController
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