@extends('layouts.app')

@section('content')
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
        --red:          #A32D2D;
        --red-bg:       #FCEBEB;
    }

    *, *::before, *::after { box-sizing: border-box; }

      body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
    min-height: 100vh;
}

    .grades-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 32px 20px 60px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ── CARD BASE ── */
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

    /* ── HERO CARD ── */
    .hero-header {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 6px;
    }

    .hero-icon {
        width: 32px; height: 32px;
        background: var(--purple);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .hero-icon svg { width: 18px; height: 18px; stroke: #fff; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .hero-title {
        font-size: 1.5rem; font-weight: 800;
        color: var(--purple);
    }

    .hero-sub {
        font-size: 0.82rem; color: var(--text-muted);
        margin-bottom: 18px;
    }

    .hero-meta {
        font-size: 0.82rem; color: #555;
        margin-bottom: 4px;
    }

    .hero-meta strong { font-weight: 700; color: var(--text-main); }

    /* ── CHART ── */
    .chart-wrap {
        position: relative;
        height: 280px;
    }
</style>

<div class="grades-wrap">

    {{-- HERO --}}
    <div class="card">
        <div class="hero-header">
            <div class="hero-icon">
                <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="hero-title">My Quiz Results</div>
        </div>
        <p class="hero-sub">Track your performance across all quizzes</p>

        @if($recentQuizzes->isNotEmpty())
            <p class="hero-meta">Last quiz: <strong>{{ $recentQuizzes->first()->created_at->format('F d, Y') }}</strong></p>
        @endif
        <p class="hero-meta">Average score: <strong>{{ $avgScore }}%</strong></p>
    </div>

    {{-- CHART --}}
    <div class="card">
        <div class="card-title">Performance over time</div>
        <div class="chart-wrap">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(108, 99, 255, 0.18)');
    gradient.addColorStop(1, 'rgba(108, 99, 255, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Score %',
                data: @json($scores),
                borderColor: '#6C63FF',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6C63FF',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    borderColor: 'rgba(174,165,255,0.4)',
                    borderWidth: 1,
                    titleColor: '#1a1a2e',
                    bodyColor: '#534AB7',
                    titleFont: { family: 'Plus Jakarta Sans', weight: '700', size: 12 },
                    bodyFont:  { family: 'Plus Jakarta Sans', weight: '700', size: 13 },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y + '%'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(108,99,255,0.07)' },
                    ticks: {
                        stepSize: 20,
                        font: { family: 'Plus Jakarta Sans', weight: '600', size: 11 },
                        color: '#AFA9EC',
                        callback: v => v + '%'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', weight: '600', size: 11 },
                        color: '#AFA9EC'
                    }
                }
            }
        }
    });
</script>
@endsection