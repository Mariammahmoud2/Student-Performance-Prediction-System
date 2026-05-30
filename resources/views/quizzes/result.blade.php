@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --purple:       #6C63FF;
        --purple-dark:  #534AB7;
        --purple-light: #EEEDFE;
        --purple-mid:   #AFA9EC;
        --border:       rgba(174, 165, 255, 0.35);
        --text-main:    #1a1a2e;
        --text-muted:   #aaa;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
        min-height: 100vh;
    }

    .result-wrap {
        max-width: 560px;
        margin: 0 auto;
        padding: 60px 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .card {
        background: #fff;
        border: 0.5px solid var(--border);
        border-radius: 18px;
        padding: 40px 32px;
        text-align: center;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        background: var(--purple-light);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .result-icon svg {
        width: 30px;
        height: 30px;
        stroke: var(--purple);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .result-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .result-sub {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 28px;
    }

    .prediction-box {
        display: inline-block;
        background: var(--purple-light);
        border: 1.5px solid var(--purple-mid);
        border-radius: 16px;
        padding: 20px 48px;
        margin-bottom: 32px;
    }

    .prediction-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--purple-mid);
        margin-bottom: 6px;
    }

    .prediction-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--purple);
        letter-spacing: 0.03em;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s;
    }

    .btn-back:hover { color: var(--purple); }
</style>
@endsection

@section('content')

<div class="result-wrap">
    <div class="card">

        <div class="result-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>

        <div class="result-title">Performance Analysis</div>
        <div class="result-sub">Based on your answers, our AI model predicts:</div>

        <div class="prediction-box">
            <div class="prediction-label">Your prediction</div>
            <div class="prediction-value">{{ $prediction }}</div>
        </div>

        <br>

        <a href="{{ route('dashboard') }}" class="btn-back">
            ← Back to Dashboard
        </a>

    </div>
</div>

@endsection