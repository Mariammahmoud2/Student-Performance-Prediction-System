<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizViewController;  
use Illuminate\Support\Facades\Route;
use App\Models\QuizSession;
use App\Http\Controllers\GradeController;
// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// لوحة التحكم (Dashboard)
 Route::get('/dashboard', [QuizViewController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');
// مسارات الكويز (Quiz Routes)
Route::middleware(['auth', 'verified'])->group(function () {
    // عرض قائمة المجموعات (Index)
    Route::get('/quizzes', [QuizViewController::class, 'index'])->name('quizzes.index');
    
    // عرض سؤال واحد من مجموعة محددة (Show)
    Route::get('/quizzes/{batch}', [QuizViewController::class, 'show'])->name('quizzes.show');
    
    // حفظ الإجابة والانتقال للسؤال التالي (Save)
    Route::post('/quizzes/save', [QuizViewController::class, 'saveAnswer'])->name('quizzes.save');

    // مسار مؤقت للدرجات
    Route::get('/grades', fn() => view('dashboard'))->name('grades.index');
});

// روابط الملف الشخصي (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');

require __DIR__.'/auth.php';