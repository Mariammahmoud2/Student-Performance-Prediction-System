@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 pb-12">
    {{-- 1. شريط التقدم (Progress Bar) --}}
    @php
        $totalQuestions = 10; 
        $currentPage = $questions->currentPage();
        $progress = ($currentPage / $totalQuestions) * 100;
    @endphp
    
    <div class="mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-bold text-[#6366f1]">Progress: {{ $progress }}%</span>
            <span class="text-sm text-gray-500 font-medium">Question {{ $currentPage }} of {{ $totalQuestions }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 shadow-sm">
            <div class="bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    {{-- 2. بطاقة السؤال --}}
    <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
        <div class="bg-indigo-50/50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-indigo-900">
                <i class="fas fa-pencil-alt ml-2"></i> Quiz Assessment
            </h3>
            <span class="bg-white text-[#6366f1] text-xs font-bold px-3 py-1 rounded-lg shadow-sm border border-indigo-100">
                Batch #{{ $batch }}
            </span>
        </div>

        <div class="p-8">
            @foreach ($questions as $question)
                <form action="{{ route('quizzes.save') }}" method="POST" id="quizForm">
                    @csrf
                    {{-- البيانات المخفية للربط --}}
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <input type="hidden" name="batch" value="{{ $batch }}">
                    <input type="hidden" name="next_page" value="{{ $currentPage + 1 }}">
                    <input type="hidden" name="quiz_session_id" value="{{ $sessionId }}">

                    {{-- نص السؤال --}}
                    <div class="mb-10 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                            {{ $question->question_text }}
                        </h2>
                    </div>

                    {{-- 3. عرض الخيارات بناءً على النوع --}}
                    <div class="mb-10">
                        @if($question->input_type === 'select')
                            {{-- شكل الخيارات العادية (Radio Buttons) --}}
                            <div class="space-y-4">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-4 p-5 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-[#6366f1] hover:bg-indigo-50/50 transition-all group">
                                        <input type="radio" name="answer" value="{{ $option }}" 
                                               class="w-5 h-5 shrink-0 text-[#6366f1] border-gray-300 focus:ring-[#6366f1]" required>
                                        <span class="text-gray-700 group-hover:text-[#6366f1] font-semibold text-lg">
                                            {{ $option }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->input_type === 'number')
                            {{-- شكل الـ Slider Rating Scale --}}
                            <div class="px-4">
                                <div class="text-center mb-8">
                                    <span id="rangeValue" class="text-6xl font-black text-[#6366f1] bg-indigo-50 px-8 py-3 rounded-3xl border-2 border-indigo-100 shadow-inner">
                                        {{ $question->min ?? 1 }}
                                    </span>
                                </div>

                                <div class="relative pt-6 pb-12">
                                    <input type="range" name="answer" id="ratingSlider"
                                           min="{{ $question->min ?? 1 }}" max="{{ $question->max ?? 5 }}" 
                                           value="{{ $question->min ?? 1 }}" step="1" required
                                           class="slider-input w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#6366f1]"
                                           oninput="document.getElementById('rangeValue').innerText = this.value">

                                    {{-- الأرقام أسفل السلايدر --}}
                                    <div class="flex justify-between mt-6 px-1 text-sm font-bold text-gray-400">
                                        @for ($i = ($question->min ?? 1); $i <= ($question->max ?? 5); $i++)
                                            <div class="flex flex-col items-center">
                                                <span class="mb-1">|</span>
                                                <span>{{ $i }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="flex justify-between mt-2 px-1">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Poor</span>
                                        <span class="text-xs font-bold text-gray-400 uppercase">Excellent</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 4. أزرار التحكم --}}
                    <div class="flex justify-between items-center pt-8 border-t border-gray-100">
                        @if($currentPage > 1)
                            <a href="{{ $questions->previousPageUrl() . '&batch=' . $batch }}" 
                               class="flex items-center gap-2 text-gray-400 font-bold hover:text-[#6366f1] transition-colors py-2 px-4">
                                <i class="fas fa-arrow-left"></i> Previous
                            </a>
                        @else
                            <div></div>
                        @endif

                        <button type="submit" 
                                class="bg-gradient-to-r from-[#6366f1] to-[#4f46e5] text-white px-12 py-4 rounded-2xl font-bold text-lg shadow-lg hover:shadow-indigo-300 transition-all active:scale-95 flex items-center gap-3">
                            {{ $questions->onLastPage() ? 'Finish Quiz' : 'Next Question' }}
                            <i class="fas {{ $questions->onLastPage() ? 'fa-check-circle' : 'fa-arrow-right' }}"></i>
                        </button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* تنسيق السلايدر ليكون مطابقاً للصورة */
    .slider-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 32px;
        height: 32px;
        background: #ffffff;
        border: 5px solid #6366f1;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        transition: transform 0.2s;
    }
    .slider-input::-webkit-slider-thumb:hover {
        transform: scale(1.15);
    }
</style>

<script>
    // تلوين الخيار المختار في أسئلة الـ Radio
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('label').forEach(l => l.classList.remove('border-[#6366f1]', 'bg-indigo-50/50'));
            if (this.checked) {
                this.closest('label').classList.add('border-[#6366f1]', 'bg-indigo-50/50');
            }
        });
    });
</script>
@endsection