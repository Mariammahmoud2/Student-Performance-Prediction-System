<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizApiController extends Controller
{
    // بدء جلسة اختبار (يرجع JSON)
    public function startSession(Request $request)
    {
        $session = QuizSession::create([
            'student_name' => $request->student_name,
            'current_batch' => 1,
            'is_completed' => false
        ]);

        return response()->json([
            'status' => 'success',
            'session_id' => $session->id
        ]);
    }

    // جلب أسئلة باتش معين كـ JSON
    public function getQuestions($batchNumber)
    {
        $questions = Question::where('batch_number', $batchNumber)->get();
        return response()->json($questions);
    }
}