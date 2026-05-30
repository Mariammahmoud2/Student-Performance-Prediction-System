@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --purple:       #6C63FF;
        --purple-dark:  #534AB7;
        --purple-light: #EEEDFE;
        --purple-mid:   #9B88FF;
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
        min-height: 100vh;
    }

    .quiz-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 40px 20px 60px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .page-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 4px;
    }

    .page-sub {
        font-size: 0.85rem;
        color: #aaa;
        margin-bottom: 8px;
    }

    .timeline-wrapper {
        background: #fff;
        border-radius: 20px;
        border: 0.5px solid rgba(174, 165, 255, 0.35);
        padding: 36px 32px 32px;
    }

    @media (max-width: 600px) {
        .timeline-wrapper { padding: 24px 16px; }
    }

    /* ── TIMELINE ROW ── */
    .timeline-row {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 0;
        margin-bottom: 32px;
    }

    .tl-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 160px;
        text-align: center;
    }

    @media (max-width: 500px) {
        .tl-step { width: 100px; }
    }

    .tl-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }

    .tl-circle.active {
        background: var(--purple-light);
        border: 2.5px solid var(--purple);
    }

    .tl-circle.upcoming {
        background: #f4f3ff;
        border: 2px solid #e0deff;
    }

    .tl-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 3px;
        line-height: 1.3;
    }

    .tl-step.upcoming .tl-name {
        color: #aaa;
    }

    .tl-sub {
        font-size: 0.73rem;
        color: #bbb;
    }

    .tl-line {
        flex: 1;
        height: 2px;
        margin-top: 27px;
        background: #e0deff;
    }

    /* ── STEPS LABELS ── */
    .steps-info {
        display: flex;
        justify-content: center;
        gap: 6px;
        align-items: center;
        margin-bottom: 28px;
    }

    .step-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 99px;
    }

    .step-badge.done {
        background: var(--purple-light);
        color: var(--purple-dark);
    }

    .step-badge.inactive {
        background: #f0f0f4;
        color: #bbb;
    }

    .step-arrow {
        color: #ddd;
        font-size: 0.75rem;
    }

    /* ── NOTE ── */
    .timeline-note {
        text-align: center;
        font-size: 0.8rem;
        color: #aaa;
        margin-bottom: 28px;
        line-height: 1.6;
    }

    .timeline-note span { color: var(--purple); font-weight: 600; }

    /* ── CTA ── */
    .btn-big-start {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 36px;
        background: var(--purple);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s, transform 0.1s;
        box-shadow: 0 4px 16px rgba(108,99,255,0.30);
    }

    .btn-big-start:hover  { background: var(--purple-dark); color: #fff; }
    .btn-big-start:active { transform: scale(0.97); }
    .btn-big-start i      { font-size: 18px; }
</style>
@endsection

@section('content')

<div class="quiz-wrap">

    <div>
        <div class="page-title">All Quizzes</div>
        <div class="page-sub">3 sections — complete them in order to unlock your prediction</div>
    </div>

    <div class="timeline-wrapper">

        {{-- Steps row --}}
        <div class="timeline-row">

            <div class="tl-step">
                <div class="tl-circle active">🏠</div>
                <div class="tl-name">Home & Family Life</div>
                <div class="tl-sub">10 questions · ~5 min</div>
            </div>

            <div class="tl-line"></div>

            <div class="tl-step upcoming">
                <div class="tl-circle upcoming">🎓</div>
                <div class="tl-name">School & Academic Life</div>
                <div class="tl-sub">10 questions · ~5 min</div>
            </div>

            <div class="tl-line"></div>

            <div class="tl-step upcoming">
                <div class="tl-circle upcoming">🧠</div>
                <div class="tl-name">Habits & Mindset</div>
                <div class="tl-sub">10 questions · ~5 min</div>
            </div>

        </div>

        {{-- Badge labels --}}
        <div class="steps-info">
            <span class="step-badge done">Start here</span>
            <span class="step-arrow">→</span>
            <span class="step-badge inactive">Up next</span>
            <span class="step-arrow">→</span>
            <span class="step-badge inactive">Then this</span>
        </div>

        {{-- Note --}}
        <div class="timeline-note">
            Answer all <span>30 questions</span> across the 3 sections — you'll move between them automatically.
        </div>

        {{-- CTA --}}
        <div style="text-align: center;">
            <form method="POST" action="{{ route('quizzes.start', 1) }}">
                @csrf
                <button type="submit" class="btn-big-start">
                    <i class="ti ti-player-play" aria-hidden="true"></i>
                    Start from the beginning
                </button>
            </form>
        </div>

    </div>
</div>

@endsection