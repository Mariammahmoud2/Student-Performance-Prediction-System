@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] py-16 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl font-extrabold mb-4">All Quizzes</h1>
            <p class="text-indigo-100 text-lg">
                {{ $batches->count() }} quizzes available • Complete them all to test your knowledge
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($batches as $batch)
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col items-center p-8 border border-gray-100 group">
                        
                        <div class="w-16 h-16 bg-[#6366f1] rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-align-left text-2xl text-white"></i>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2">Quiz {{ $batch->batch_number }}</h3>
                        <p class="text-gray-400 text-sm mb-6">10 questions • 15 min</p>

                        <a href="{{ route('quizzes.show', $batch->batch_number) }}" 
                           class="text-[#6366f1] font-semibold hover:underline flex items-center gap-1">
                            Start Quiz →
                        </a>
                    </div>
                @endforeach
            </div>

            @if($batches->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-dashed border-gray-300">
                    <i class="fas fa-folder-open text-6xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 text-xl font-medium">No quizzes available right now.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection