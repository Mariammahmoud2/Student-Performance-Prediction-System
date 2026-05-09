<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizApiController;

// مسار حفظ الإجابة عبر الـ API
Route::post('/submit-answer', [QuizApiController::class, 'submitAnswer']);