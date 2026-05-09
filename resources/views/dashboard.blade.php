@extends('layouts.app')

@section('content')
<style>
    .badge-pass { background: #d1fae5; color: #059669; }
    .badge-fail { background: #ffe4e6; color: #e11d48; }
    .quiz-row:last-child { border-bottom: none; }
</style>

<div class="min-h-screen bg-gradient-to-r from-[#6366f1] to-[#8b5cf6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- البطاقة الكبيرة --}}
        <div class="bg-white rounded-3xl p-10 mb-6">
            <h1 class="text-3xl font-black text-gray-800 mb-2">
                Welcome   👋 {{ Auth::user()->name }}
            </h1>
            <p class="text-gray-400 text-base mb-8">
                Track your academic progress and get AI-powered insights to improve your performance.
            </p>

            <hr class="border-gray-100 mb-6">

            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-3xl font-black text-[#6366f1] mb-1">{{ $completedCount }}</p>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Quizzes Completed</p>
                </div>
                <div class="border-x border-gray-100">
                    <p class="text-3xl font-black text-[#6366f1] mb-1">{{ $avgScore }}%</p>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Average Score</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-[#6366f1] mb-1">{{ $grade }}</p>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest"> Grade</p>
                </div>
            </div>

            <hr class="border-gray-100 mt-6">
        </div>

        {{-- الـ 3 كروت --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            {{-- Average Grade --}}
            <div class="bg-white rounded-2xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Average Grade</p>
                    <div class="w-10 h-10 bg-[#6366f1] rounded-xl flex items-center justify-center">
                        <i class="fas fa-percent text-white text-sm"></i>
                    </div>
                </div>
                <p class="text-gray-800 text-4xl font-black mb-3">{{ $avgScore }}%</p>
                <p class="text-emerald-500 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-up text-xs"></i>
                    {{ $grade }} average
                </p>
            </div>

            {{-- Highest Score --}}
            <div class="bg-white rounded-2xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Highest Score</p>
                    <div class="w-10 h-10 bg-[#10b981] rounded-xl flex items-center justify-center">
                        <i class="fas fa-crown text-white text-sm"></i>
                    </div>
                </div>
                <p class="text-gray-800 text-4xl font-black mb-3">{{ $highestScore }}%</p>
                <p class="text-emerald-500 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-star text-xs"></i>
                    {{ $highestName }}
                </p>
            </div>

            {{-- Lowest Score --}}
            <div class="bg-white rounded-2xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Lowest Score</p>
                    <div class="w-10 h-10 bg-[#38bdf8] rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-white text-sm"></i>
                    </div>
                </div>
                <p class="text-gray-800 text-4xl font-black mb-3">{{ $lowestScore }}%</p>
                <p class="text-red-400 text-sm font-bold flex items-center gap-1">
                    <i class="fas fa-exclamation-circle text-xs"></i>
                    Needs work
                </p>
            </div>

        </div>

        {{-- جدول السجل --}}
        <div class="bg-white rounded-3xl p-8">
            <div class="flex justify-between items-center mb-6 px-2">
                <span class="text-gray-400 text-xs font-black uppercase tracking-widest">Session</span>
                <span class="text-gray-400 text-xs font-black uppercase tracking-widest">Result Date</span>
            </div>

            <div>
                @forelse($recentQuizzes as $quiz)
                    @php
                        $isPass = in_array($quiz->prediction, ['Excellent', 'Pass', 'Good']);
                    @endphp
                    <div class="quiz-row flex justify-between items-center py-5 px-2 border-b border-gray-50 hover:bg-gray-50 rounded-xl transition-colors">
                        <span class="text-lg font-black text-gray-800">{{ $quiz->quiz_name }}</span>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-400 text-sm">{{ $quiz->created_at->format('M d, Y') }}</span>
                            <span class="px-5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $isPass ? 'badge-pass' : 'badge-fail' }}">
                                {{ $quiz->prediction }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">No sessions recorded yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection