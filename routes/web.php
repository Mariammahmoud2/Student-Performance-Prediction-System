<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizViewController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [QuizViewController::class, 'dashboard'])
        ->name('dashboard');

    Route::middleware(['verified'])->group(function () {

        Route::get('/quizzes', [QuizViewController::class, 'index'])
            ->name('quizzes.index');

        // static قبل dynamic عشان /quizzes/save ميتعاملش معاه كـ batch
        Route::post('/quizzes/save', [QuizViewController::class, 'saveAnswer'])
            ->name('quizzes.save');

        Route::post('/quizzes/{batch}/start', [QuizViewController::class, 'start'])
            ->name('quizzes.start');

        Route::get('/quizzes/{batch}', [QuizViewController::class, 'show'])
            ->name('quizzes.show');

        Route::get('/grades', [GradeController::class, 'index'])
            ->name('grades.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';