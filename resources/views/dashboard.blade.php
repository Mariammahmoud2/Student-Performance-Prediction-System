@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --purple:       #6C63FF;
        --purple-dark:  #534AB7;
        --purple-light: #EEEDFE;
        --purple-mid:   #AFA9EC;
        --purple-bg:    #f0effe;
        --border:       rgba(174, 165, 255, 0.35);
        --text-main:    #1a1a2e;
        --text-muted:   #aaa;
        --green:        #0F6E56;
        --green-bg:     #E1F5EE;
        --amber:        #854F0B;
        --red:          #A32D2D;
        --red-bg:       #FCEBEB;
    }

    *, *::before, *::after { box-sizing: border-box; }

     body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
    min-height: 100vh;
}

    /* ── LAYOUT ── */
    .dash-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 32px 20px 60px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ── WELCOME CARD ── */
    .welcome-card {
        background: #fff;
        border: 0.5px solid var(--border);
        border-radius: 18px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .welcome-left { display: flex; align-items: center; gap: 14px; }

    .avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: var(--purple-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; color: #3C3489;
        flex-shrink: 0;
    }

    .welcome-name { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
    .welcome-sub  { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

    .prediction-pill {
        display: flex; align-items: center; gap: 8px;
        background: var(--purple-light);
        border-radius: 99px;
        padding: 8px 16px;
        flex-shrink: 0;
    }

    .prediction-pill .pill-label { font-size: 0.68rem; color: var(--purple-dark); font-weight: 600; }
    .prediction-pill .pill-value { font-size: 0.9rem; font-weight: 700; color: #3C3489; }

    @media (max-width: 560px) {
        .welcome-card { flex-direction: column; align-items: flex-start; }
    }

    /* ── STATS ROW ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    @media (max-width: 560px) {
        .stats-row { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: #fff;
        border: 0.5px solid var(--border);
        border-radius: 14px;
        padding: 18px;
    }

    .stat-label {
        font-size: 0.68rem; font-weight: 700;
        color: #c5c0e8;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 1.6rem; font-weight: 800;
        color: var(--text-main);
        margin-bottom: 4px; line-height: 1;
    }

    .stat-sub { font-size: 0.72rem; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .stat-sub.green { color: var(--green); }
    .stat-sub.amber { color: var(--amber); }
    .stat-sub.gray  { color: #ccc; }

    /* ── SECTIONS / HISTORY CARD ── */
    .card {
        background: #fff;
        border: 0.5px solid var(--border);
        border-radius: 18px;
        padding: 22px 24px;
    }

    .card-title {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: #c5c0e8;
        margin-bottom: 14px;
    }

    /* ── SECTION ROWS ── */
    .section-row {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 0;
        border-bottom: 0.5px solid #f3f0ff;
    }

    .section-row:last-of-type { border-bottom: none; }

    .sec-icon {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: var(--purple-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }

    .sec-name  { font-size: 0.85rem; font-weight: 600; color: var(--text-main); flex: 1; }
    .sec-count { font-size: 0.72rem; color: #ccc; margin-right: 6px; }

    .sec-badge {
        font-size: 0.68rem; font-weight: 700;
        padding: 3px 10px; border-radius: 99px;
        text-transform: uppercase; letter-spacing: 0.03em;
    }

    .sec-badge.done { background: var(--green-bg); color: var(--green); }
    .sec-badge.todo { background: #f3f0ff; color: var(--purple-mid); }

    /* ── BUTTONS ── */
    .btn-continue {
        margin-top: 16px; width: 100%; padding: 12px;
        background: var(--purple); color: #fff;
        border: none; border-radius: 12px;
        font-size: 0.88rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-continue:hover { background: var(--purple-dark); color: #fff; }

    .btn-start {
        margin-top: 16px; width: 100%; padding: 12px;
        background: var(--purple-light); color: var(--purple-dark);
        border: none; border-radius: 12px;
        font-size: 0.88rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-start:hover { background: #dddaff; color: var(--purple-dark); }

    /* ── HISTORY ── */
    .attempt-row {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 0;
        border-bottom: 0.5px solid #f3f0ff;
    }

    .attempt-row:last-child { border-bottom: none; padding-bottom: 0; }

    .attempt-num-wrap {
        display: flex; flex-direction: column; align-items: center;
    }

    .attempt-circle {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--purple-light);
        border: 1.5px solid var(--purple-mid);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; color: var(--purple-dark);
        flex-shrink: 0;
    }

    .attempt-line {
        width: 1.5px; flex: 1; min-height: 16px;
        background: #ddd9ff;
        margin-top: 4px;
    }

    .attempt-body { flex: 1; padding-top: 4px; }
    .attempt-label { font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
    .attempt-date  { font-size: 0.72rem; color: var(--text-muted); }

    .attempt-badge {
        display: inline-block;
        font-size: 0.68rem; font-weight: 700;
        padding: 3px 10px; border-radius: 99px;
        text-transform: uppercase; letter-spacing: 0.03em;
        margin-top: 6px;
    }

    .attempt-badge.excellent { background: var(--purple-light); color: var(--purple-dark); }
    .attempt-badge.good,
    .attempt-badge.pass      { background: var(--green-bg);     color: var(--green); }
    .attempt-badge.fail      { background: var(--red-bg);       color: var(--red); }
    .attempt-badge.default   { background: #f3f0ff;             color: #ccc; }

    .empty-state {
        text-align: center;
        padding: 28px 0;
        color: #ccc;
        font-size: 0.82rem;
    }
</style>
@endsection

@section('content')

@php
    $initials = collect(explode(' ', Auth::user()->name))
                    ->map(fn($w) => strtoupper($w[0]))
                    ->take(2)
                    ->implode('');

    $inProgressSession = \App\Models\QuizSession::where('user_id', auth()->id())
        ->inProgress()
        ->latest()
        ->first();

    $completedBatches = [];
    if ($inProgressSession) {
        $totalPerBatch = \App\Models\Question::select('batch_number', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('batch_number')
            ->pluck('total', 'batch_number');

        $answeredPerBatch = \App\Models\QuizAnswer::where('quiz_session_id', $inProgressSession->id)
            ->with('question')
            ->get()
            ->groupBy('question.batch_number')
            ->map->count();

        $completedBatches = $totalPerBatch->filter(
            fn($total, $batch) => ($answeredPerBatch[$batch] ?? 0) >= $total
        )->keys()->toArray();
    }

    $nextBatch = null;
    if ($inProgressSession) {
        for ($b = 1; $b <= 3; $b++) {
            if (!in_array($b, $completedBatches)) { $nextBatch = $b; break; }
        }
    }

    $sections = [
        1 => ['icon' => '🏠', 'name' => 'Home & Family Life'],
        2 => ['icon' => '🎓', 'name' => 'School & Academic Life'],
        3 => ['icon' => '🧠', 'name' => 'Habits & Mindset'],
    ];
@endphp

<div class="dash-wrap">

    {{-- WELCOME --}}
    <div class="welcome-card">
        <div class="welcome-left">
            <div class="avatar">{{ $initials }}</div>
            <div>
                <div class="welcome-name">Welcome, {{ Auth::user()->name }} 👋</div>
                <div class="welcome-sub">Track your progress and AI-powered prediction</div>
            </div>
        </div>
        @if ($grade !== 'No Data')
        <div class="prediction-pill">
            <div>
                <div class="pill-label">Latest prediction</div>
                <div class="pill-value">{{ $grade }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- STATS --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Attempts completed</div>
            <div class="stat-value">{{ $completedCount }}</div>
            @if ($completedCount > 0)
                <div class="stat-sub green">✓ All sections answered</div>
            @else
                <div class="stat-sub gray">No attempts yet</div>
            @endif
        </div>

        <div class="stat-card">
            <div class="stat-label">Latest prediction</div>
            <div class="stat-value">{{ $grade !== 'No Data' ? $grade : '—' }}</div>
            @if ($grade === 'Excellent')
                <div class="stat-sub green">↑ Top result</div>
            @elseif ($grade === 'Fail')
                <div class="stat-sub amber">! Needs improvement</div>
            @elseif ($grade !== 'No Data')
                <div class="stat-sub green">↑ Good standing</div>
            @else
                <div class="stat-sub gray">Complete a quiz first</div>
            @endif
        </div>

        <div class="stat-card">
            <div class="stat-label">Average score</div>
            <div class="stat-value">{{ $avgScore > 0 ? $avgScore . '%' : '—' }}</div>
            @if ($avgScore >= 80)
                <div class="stat-sub green">↑ Strong performance</div>
            @elseif ($avgScore > 0)
                <div class="stat-sub amber">Keep it up</div>
            @else
                <div class="stat-sub gray">No data yet</div>
            @endif
        </div>
    </div>

    {{-- SECTIONS CARD --}}
    @if ($inProgressSession)
    <div class="card">
        <div class="card-title">Current attempt — sections</div>

        @foreach ($sections as $num => $sec)
        <div class="section-row">
            <div class="sec-icon">{{ $sec['icon'] }}</div>
            <div class="sec-name">{{ $sec['name'] }}</div>
            <span class="sec-count">10 Qs</span>
            @if (in_array($num, $completedBatches))
                <span class="sec-badge done">✓ Done</span>
            @else
                <span class="sec-badge todo">Pending</span>
            @endif
        </div>
        @endforeach

        @if ($nextBatch)
        <a href="{{ route('quizzes.show', $nextBatch) }}?page=1&quiz_session_id={{ $inProgressSession->id }}"
           class="btn-continue">
            ▶ Continue — Section {{ $nextBatch }}
        </a>
        @endif
    </div>

    @elseif ($completedCount === 0)
    <div class="card">
        <div class="card-title">Your quiz sections</div>

        @foreach ($sections as $num => $sec)
        <div class="section-row">
            <div class="sec-icon">{{ $sec['icon'] }}</div>
            <div class="sec-name">{{ $sec['name'] }}</div>
            <span class="sec-count">10 Qs</span>
            <span class="sec-badge todo">Pending</span>
        </div>
        @endforeach

        <a href="{{ route('quizzes.index') }}" class="btn-continue">
            ▶ Start your first attempt
        </a>
    </div>

    @else
    <div class="card">
        <div class="card-title">Ready for another attempt?</div>

        @foreach ($sections as $num => $sec)
        <div class="section-row">
            <div class="sec-icon">{{ $sec['icon'] }}</div>
            <div class="sec-name">{{ $sec['name'] }}</div>
            <span class="sec-count">10 Qs</span>
            <span class="sec-badge todo">Pending</span>
        </div>
        @endforeach

        <a href="{{ route('quizzes.index') }}" class="btn-start">
            ＋ Start a new attempt
        </a>
    </div>
    @endif

    {{-- ATTEMPTS HISTORY --}}
    <div class="card">
        <div class="card-title">Attempts history</div>

        @forelse ($recentQuizzes as $index => $quiz)
        @php
            $attemptNum = $recentQuizzes->count() - $index;
            $badgeCls   = match($quiz->prediction) {
                'Excellent' => 'excellent',
                'Good'      => 'good',
                'Pass'      => 'pass',
                'Fail'      => 'fail',
                default     => 'default',
            };
            $isLast = $loop->last;
        @endphp

        <div class="attempt-row">
            <div class="attempt-num-wrap">
                <div class="attempt-circle">{{ $attemptNum }}</div>
                @if (!$isLast)
                    <div class="attempt-line"></div>
                @endif
            </div>
            <div class="attempt-body">
                <div class="attempt-label">Attempt {{ $attemptNum }}</div>
                <div class="attempt-date">{{ $quiz->created_at->format('M d, Y') }}</div>
                <span class="attempt-badge {{ $badgeCls }}">{{ $quiz->prediction }}</span>
            </div>
        </div>

        @empty
        <div class="empty-state">No attempts recorded yet.</div>
        @endforelse
    </div>

</div>
@endsection