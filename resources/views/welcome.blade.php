<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduPRE - Predict Your Performance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            /* خلفية محسّنة جديدة */
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%);
            background-attachment: fixed;
            color: #111;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* إضافة طبقات ناعمة في الخلفية */
        body::before {
            content: "";
            position: absolute;
            top: -20%;
            right: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.08), transparent);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.06), transparent);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .top-label {
            font-size: 11px;
            letter-spacing: 0.12em;
            color: #6366f1;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 32px;
            background: rgba(99, 102, 241, 0.08);
            padding: 6px 14px;
            border-radius: 20px;
        }

        .big-title {
            font-size: 52px;
            font-weight: 700;
            color: #1e1b4b;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .big-title em {
            font-style: normal;
            color: #6366f1;
            background: linear-gradient(to right, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .desc {
            font-size: 17px;
            color: #4b5563;
            max-width: 420px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .btns {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 56px;
            flex-wrap: wrap;
        }

        .btn-main {
            padding: 14px 40px;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }
        .btn-main:hover { 
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .btn-ghost {
            padding: 14px 40px;
            background: #fff;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-ghost:hover { border-color: #6366f1; color: #6366f1; background: #f5f3ff; }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 900px;
            width: 100%;
            margin: 0 auto 48px;
        }

        .step {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            padding: 30px 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .step-num {
            font-size: 11px;
            color: #6366f1;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e1b4b;
            margin-bottom: 8px;
        }

        .step-sub {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        .result-card {
            max-width: 350px;
            width: 100%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 32px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .meter {
            height: 8px;
            background: #f1f5f9;
            border-radius: 10px;
        }

        @media (max-width: 480px) {
            .big-title { font-size: 38px; }
            .steps { grid-template-columns: 1fr; border-radius: 24px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <p class="top-label">Student performance · AI prediction</p>
        <h1 class="big-title">
            10 questions.<br>
            <em>Your future grade.</em>
        </h1>
        <div class="btns">
            <a href="{{ route('register') }}" class="btn-main">Start prediction →</a>
            <a href="{{ route('login') }}" class="btn-ghost">Sign in</a>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step-num">Step 01</div>
                <div class="step-title">Answer</div>
                <div class="step-sub">10 smart questions about your habits</div>
            </div>
            <div class="step">
                <div class="step-num">Step 02</div>
                <div class="step-title">Analyze</div>
                <div class="step-sub">AI reads your pattern instantly</div>
            </div>
            <div class="step">
                <div class="step-num">Step 03</div>
                <div class="step-title">Predict</div>
                <div class="step-sub">Get your performance level</div>
            </div>
        </div>
    </div>
</body>
</html>