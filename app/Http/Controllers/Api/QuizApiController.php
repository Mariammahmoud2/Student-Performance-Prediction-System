<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use App\Helpers\QuestionMapper;

class QuizApiController extends Controller
{
    // ✅ جيب الـ batches المتاحة
    public function getBatches()
    {
        $batches = Question::select('batch_number')
            ->distinct()
            ->orderBy('batch_number')
            ->pluck('batch_number');

        return response()->json(['batches' => $batches]);
    }

    // ✅ ابدأ quiz جديد
    public function start(Request $request, $batch)
    {
        // abandon القديمة
        QuizSession::where('user_id', auth()->id())
            ->inProgress()
            ->update(['predicted_performance' => 'Abandoned']);

        $session = QuizSession::create([
            'user_id'               => auth()->id(),
            'predicted_performance' => 'In Progress',
        ]);

        return response()->json([
            'quiz_session_id' => $session->id,
            'batch'           => $batch,
        ]);
    }

    // ✅ جيب أسئلة batch معين
    public function getQuestions(Request $request, $batch)
    {
        $sessionId = $request->query('quiz_session_id');

        $sessionExists = QuizSession::where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->inProgress()
            ->exists();

        if (!$sessionExists) {
            return response()->json(['error' => 'Session expired'], 403);
        }

        $questions = Question::where('batch_number', $batch)
            ->get()
            ->map(function ($question) {
                return [
                    'id'           => $question->id,
                    'display_text' => QuestionMapper::getDisplayQuestion($question->question_text),
                    'input_type'   => $question->input_type,
                    'options'      => $question->input_type === 'select'
                        ? QuestionMapper::getMappedOptions($question->question_text, $question->options)
                        : null,
                ];
            });

        return response()->json([
            'batch'     => $batch,
            'questions' => $questions,
        ]);
    }

    // ✅ احفظ إجابات الـ batch كلها دفعة واحدة
    public function saveAnswers(Request $request)
    {
        $request->validate([
            'quiz_session_id' => 'required|exists:quiz_sessions,id',
            'batch'           => 'required|integer',
            'answers'         => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer'      => 'required',
        ]);

        $session = QuizSession::where('id', $request->quiz_session_id)
            ->where('user_id', auth()->id())
            ->inProgress()
            ->firstOrFail();

        foreach ($request->answers as $ans) {
            QuizAnswer::updateOrCreate(
                [
                    'user_id'         => auth()->id(),
                    'question_id'     => $ans['question_id'],
                    'quiz_session_id' => $session->id,
                ],
                ['answer' => $ans['answer']]
            );
        }

        $nextBatch       = $request->batch + 1;
        $nextBatchExists = Question::where('batch_number', $nextBatch)->exists();

        if ($nextBatchExists) {
            return response()->json([
                'status'     => 'next_batch',
                'next_batch' => $nextBatch,
            ]);
        }

        // ✅ كل الـ batches خلصت → شغّل Python
        return $this->runPrediction($session);
    }

    private function runPrediction($session)
    {
        $allAnswers = QuizAnswer::where('quiz_session_id', $session->id)
            ->orderBy('question_id')
            ->pluck('answer')
            ->toArray();

        $predictProcess = Process::input(json_encode(['answers' => $allAnswers]))
            ->run('python3 ' . base_path('scripts/predict_student.py'));

        if (!$predictProcess->successful()) {
            Log::error('Python Predict Error: ' . $predictProcess->errorOutput());
            return response()->json(['error' => 'AI Processing Error'], 500);
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

            return response()->json([
                'status'          => 'completed',
                'prediction'      => $prediction,
                'recommendations' => $recommendations,
            ]);
        }

        return response()->json([
            'status'     => 'completed',
            'prediction' => $prediction,
        ]);
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
            return [];
        } catch (\Exception $e) {
            Log::error('Recommendations Exception: ' . $e->getMessage());
            return [];
        }
    }
}