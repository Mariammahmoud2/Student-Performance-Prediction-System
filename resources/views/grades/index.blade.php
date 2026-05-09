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
        {{-- البطاقة الكبيرة --}}
<div class="bg-white rounded-3xl p-10 mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 bg-[#6366f1] rounded-lg"></div>
        <h1 class="text-3xl font-black text-[#6366f1]">My Quiz Results</h1>
    </div>
    <p class="text-gray-400 text-base mb-6">Track your performance across all quizzes</p>

    @if($recentQuizzes->isNotEmpty())
        <p class="text-gray-700 text-sm mb-2">
            Last quiz: <strong>{{ $recentQuizzes->first()->created_at->format('F d, Y') }}</strong>
        </p>
    @endif
    <p class="text-gray-700 text-sm">
        Average score: <strong>{{ $avgScore }}%</strong>
    </p>
</div>

        {{-- الرسم البياني --}}
        <div class="bg-white rounded-3xl p-8 mb-6">
            <h2 class="text-lg font-black text-gray-800 mb-6">Performance Over Time</h2>
            <div class="h-[300px]">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        
       

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Score %',
                data: @json($scores),
                borderColor: '#6366f1',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#f8fafc' },
                    ticks: { font: { weight: 'bold' }, color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' }, color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection