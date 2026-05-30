@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --purple: #6C63FF;
        --purple-dark: #534AB7;
        --purple-light: #EEEDFE;
        --purple-mid: #9B88FF;
    }

 body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
    min-height: 100vh;
}
    /* ── TOP PROGRESS BAR ── */
    .quiz-progress-bar {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: #e0deff;
        z-index: 9999;
    }

    .quiz-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--purple), var(--purple-mid));
        transition: width 0.4s ease;
    }

    /* ── HEADER ── */
    .quiz-header {
        background: #fff;
        border-bottom: 1px solid #ede9ff;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 4px;
        z-index: 100;
    }

    .quiz-header .batch-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .batch-icon {
        width: 38px; height: 38px;
        background: var(--purple-light);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }

    .batch-title { font-size: 0.9rem; font-weight: 700; color: #1a1a2e; }
    .batch-sub   { font-size: 0.75rem; color: #999; }

    .q-counter {
        font-size: 0.85rem;
        color: var(--purple-dark);
        font-weight: 600;
        background: var(--purple-light);
        padding: 6px 14px;
        border-radius: 99px;
    }

    /* ── MAIN CONTENT ── */
    .quiz-body {
        max-width: 680px;
        margin: 0 auto;
        padding: 40px 20px 80px;
    }

    /* ── QUESTION CARD ── */
    .question-card {
        background: #fff;
        border: 1.5px solid #e8e5ff;
        border-radius: 20px;
        padding: 32px 28px 28px;
        box-shadow: 0 4px 24px rgba(108, 99, 255, 0.08);
        margin-bottom: 8px;
    }

    .question-card .number-input-wrap {
        border: none;
        padding: 0;
        margin-bottom: 28px;
    }

    /* Question number label */
    .q-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--purple);
        margin-bottom: 10px;
    }

    /* Question text */
    .q-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.5;
        margin-bottom: 32px;
    }

    /* ── SELECT OPTIONS ── */
    .options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 36px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 1.5px solid #e8e5ff;
        border-radius: 14px;
        background: #fff;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s, transform 0.1s;
        user-select: none;
    }

    .option-item:hover {
        border-color: var(--purple-mid);
        background: #faf9ff;
        transform: translateX(4px);
    }

    .option-item.selected {
        border-color: var(--purple);
        background: var(--purple-light);
    }

    .option-radio {
        width: 22px; height: 22px;
        border: 2px solid #d0cbff;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }

    .option-item.selected .option-radio {
        border-color: var(--purple);
        background: var(--purple);
    }

    .option-item.selected .option-radio::after {
        content: '';
        width: 8px; height: 8px;
        background: #fff;
        border-radius: 50%;
        display: block;
    }

    .option-text {
        font-size: 0.95rem;
        font-weight: 500;
        color: #2d2d44;
    }

    .option-item.selected .option-text { color: var(--purple-dark); }

    /* Hidden radio */
    .option-item input[type="radio"] { display: none; }

    /* ── NUMBER / SLIDER ── */
    .number-input-wrap {
        background: #fff;
        border: 1.5px solid #e8e5ff;
        border-radius: 16px;
        padding: 24px 20px;
        margin-bottom: 36px;
    }

    .slider-value-display {
        text-align: center;
        margin-bottom: 16px;
    }

    .slider-value-display .num {
        font-size: 3rem;
        font-weight: 800;
        color: var(--purple);
        line-height: 1;
    }

    .slider-value-display .unit {
        font-size: 0.8rem;
        color: #aaa;
        margin-top: 4px;
    }

    .slider-track {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 6px;
    }

    .slider-limit {
        font-size: 0.8rem;
        color: #bbb;
        font-weight: 600;
        min-width: 24px;
        text-align: center;
    }

    input[type="range"] {
        flex: 1;
        -webkit-appearance: none;
        height: 6px;
        border-radius: 99px;
        background: #e0deff;
        outline: none;
        cursor: pointer;
    }

    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 22px; height: 22px;
        border-radius: 50%;
        background: var(--purple);
        box-shadow: 0 2px 10px rgba(108,99,255,0.40);
        cursor: pointer;
        transition: transform 0.1s;
    }

    input[type="range"]::-webkit-slider-thumb:active { transform: scale(1.2); }

    /* ── NAVIGATION ── */
    .quiz-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .btn-prev {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 11px 20px;
        border: 1.5px solid #e0deff;
        border-radius: 12px;
        background: transparent;
        color: #888;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s;
    }

    .btn-prev:hover { border-color: var(--purple-mid); color: var(--purple); }

    .btn-next {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 28px;
        background: var(--purple);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        box-shadow: 0 4px 16px rgba(108,99,255,0.35);
    }

    .btn-next:hover  { background: var(--purple-dark); }
    .btn-next:active { transform: scale(0.97); }

    /* ── STEPS DOTS ── */
    .steps-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 28px;
    }

    .step-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #ddd;
        transition: all 0.2s;
    }

    .step-dot.done    { background: var(--purple-mid); }
    .step-dot.current { background: var(--purple); width: 22px; border-radius: 4px; }

    /* ── BATCH TRANSITION NOTICE ── */
    .next-batch-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff8e7;
        border: 1px solid #ffe8a0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.82rem;
        color: #9a6f00;
        font-weight: 500;
        margin-bottom: 28px;
    }

    /* ── OVERALL PROGRESS ── */
    .overall-progress {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 32px;
    }

    .overall-progress .label { font-size: 0.8rem; color: #aaa; white-space: nowrap; }

    .overall-bar-bg {
        flex: 1;
        height: 5px;
        background: #e8e5ff;
        border-radius: 99px;
        overflow: hidden;
    }

    .overall-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--purple), var(--purple-mid));
        border-radius: 99px;
        transition: width 0.4s ease;
    }

    .overall-progress .pct { font-size: 0.8rem; font-weight: 700; color: var(--purple); }
</style>
@endsection

@section('content')

{{-- Top progress fill bar --}}
@php
    $question    = $questions->first();
    $currentPage = request('page', 1);
    $totalPages  = $questions->total();

    // Overall progress across all 3 batches
    $batchOffset  = ($batch - 1) * 10;
    $overallDone  = $batchOffset + ($currentPage - 1);
    $overallTotal = 30;
    $overallPct   = round(($overallDone / $overallTotal) * 100);

    // Batch meta
    $batchMeta = [
        1 => ['icon' => '🏠', 'label' => 'Home & Family Life',    'sub' => 'Batch 1 of 3'],
        2 => ['icon' => '🎓', 'label' => 'School & Academic Life', 'sub' => 'Batch 2 of 3'],
        3 => ['icon' => '🧠', 'label' => 'Habits & Mindset',       'sub' => 'Batch 3 of 3'],
    ];
    $meta = $batchMeta[$batch];

    $isLastQuestion = ($currentPage >= $totalPages);
    $isLastBatch    = ($batch == 3);
@endphp

<div class="quiz-progress-bar">
    <div class="quiz-progress-bar-fill" style="width: {{ $overallPct }}%"></div>
</div>

{{-- Sticky header --}}
<div class="quiz-header">
    <div class="batch-info">
        <div class="batch-icon">{{ $meta['icon'] }}</div>
        <div>
            <div class="batch-title">{{ $meta['label'] }}</div>
            <div class="batch-sub">{{ $meta['sub'] }}</div>
        </div>
    </div>
    <div class="q-counter">
        Q{{ $overallDone + 1 }} / 30
    </div>
</div>

{{-- Main body --}}
<div class="quiz-body">

    {{-- Overall progress --}}
    <div class="overall-progress">
        <span class="label">Overall</span>
        <div class="overall-bar-bg">
            <div class="overall-bar-fill" style="width: {{ $overallPct }}%"></div>
        </div>
        <span class="pct">{{ $overallPct }}%</span>
    </div>

    {{-- Dots for current batch --}}
    <div class="steps-dots">
        @for ($i = 1; $i <= $totalPages; $i++)
            <div class="step-dot {{ $i < $currentPage ? 'done' : ($i == $currentPage ? 'current' : '') }}"></div>
        @endfor
    </div>

    @foreach ($questions as $question)
    @php
        $inputType = $question->input_type;
        $options   = $question->mapped_options ?? [];
        $qNum      = $batchOffset + $currentPage;
    @endphp

    {{-- Last question hint --}}
    @if ($isLastQuestion && !$isLastBatch)
    <div class="next-batch-hint">
          After this question, you'll move to the next section  
    </div>
    @endif

    @if ($isLastQuestion && $isLastBatch)
    <div class="next-batch-hint" style="background:#edfff4; border-color:#9eecc0; color:#1a7a45;">
         This is the last question! Your prediction will be ready after submitting.
    </div>
    @endif

    {{-- Question + Answers Card --}}
    <div class="question-card">
        <div class="q-label">Question {{ $currentPage }} of {{ $totalPages }}</div>
        <div class="q-text">{{ $question->display_text }}</div>

        <form method="POST" action="{{ route('quizzes.save') }}" id="quiz-form">
            @csrf
            <input type="hidden" name="question_id"    value="{{ $question->id }}">
            <input type="hidden" name="batch"           value="{{ $batch }}">
            <input type="hidden" name="next_page"       value="{{ $currentPage + 1 }}">
            <input type="hidden" name="quiz_session_id" value="{{ $sessionId }}">

            {{-- SELECT → option cards --}}
            @if ($inputType === 'select')
                <input type="hidden" name="answer" id="selected-answer" value="">
                <div class="options-list">
                    @foreach ($options as $opt)
                    <div class="option-item" onclick="selectOption('{{ $opt['value'] }}', this)">
                        <div class="option-radio"></div>
                        <span class="option-text">{{ $opt['display'] }}</span>
                    </div>
                    @endforeach
                </div>

            {{-- NUMBER → slider --}}
            @elseif ($inputType === 'number')
                @php
                    $min = (int)($question->min ?? 1);
                    $max = (int)($question->max ?? 10);
                    $mid = (int)(($min + $max) / 2);
                @endphp
                <div class="number-input-wrap">
                    <div class="slider-value-display">
                        <div class="num" id="slider-num">{{ $mid }}</div>
                        <div class="unit">Move the slider to select your answer</div>
                    </div>
                    <div class="slider-track">
                        <span class="slider-limit">{{ $min }}</span>
                        <input type="range"
                               name="answer"
                               min="{{ $min }}"
                               max="{{ $max }}"
                               step="1"
                               value="{{ $mid }}"
                               id="num-slider"
                               oninput="document.getElementById('slider-num').textContent = this.value"
                        >
                        <span class="slider-limit">{{ $max }}</span>
                    </div>
                </div>
            @endif

            {{-- Navigation داخل الكارت --}}
            <div class="quiz-nav">
                @if ($currentPage > 1)
                    <a href="{{ route('quizzes.show', $batch) }}?page={{ $currentPage - 1 }}&quiz_session_id={{ $sessionId }}" class="btn-prev">
                        ← Back
                    </a>
                @else
                    <div></div>
                @endif

                <button type="submit" class="btn-next" id="submit-btn">
                    @if ($isLastQuestion && $isLastBatch)
                        Get My Prediction  
                    @elseif ($isLastQuestion)
                        Next Section →
                    @else
                        Next Question →
                    @endif
                </button>
            </div>
        </form>
    </div>{{-- end .question-card --}}

    @endforeach

</div>

@endsection

@section('scripts')
<script>
function selectOption(value, el) {
    document.querySelectorAll('.option-item').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selected-answer').value = value;
}

// Validate select before submit
document.getElementById('quiz-form').addEventListener('submit', function(e) {
    const hidden = document.getElementById('selected-answer');
    if (hidden && hidden.value === '') {
        e.preventDefault();
        alert('Please select an answer before continuing.');
    }
});
</script>
@endsection