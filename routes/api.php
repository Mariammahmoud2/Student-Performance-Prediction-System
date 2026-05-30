<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\GradeApiController;
use App\Http\Controllers\Api\AuthApiController;  

 Route::post('/login',    [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

 Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthApiController::class, 'logout']);
     
    Route::get('/batches',                [QuizApiController::class, 'getBatches']);
    Route::post('/quiz/start/{batch}',    [QuizApiController::class, 'start']);
    Route::get('/quiz/{batch}/questions', [QuizApiController::class, 'getQuestions']);
    Route::post('/quiz/save-answers',     [QuizApiController::class, 'saveAnswers']);

    Route::get('/grades',                 [GradeApiController::class, 'index']);

});