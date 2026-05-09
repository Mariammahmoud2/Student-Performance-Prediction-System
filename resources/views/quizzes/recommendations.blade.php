@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 50%, #f5f7fa 100%); min-height: 100vh; padding: 2rem 1rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="display: inline-block; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 12px; padding: 6px 14px; border-radius: 20px; margin-bottom: 1rem; font-weight: 600;">
            Results
        </div>
            
            <h1 style="font-size: 48px; font-weight: 700; color: #1e1b4b; margin: 0 0 0.5rem;">
                 Your results
            </h1>
            
            <p style="font-size: 20px; color: #ef4444; margin-top: 1rem;"> ❌ You did not succeed - please improve your answers </p>
        </div>

        <!-- Recommendations -->
        <div style="margin-bottom: 3rem;">
            <h2 style="font-size: 20px; font-weight: 600; color: #1e1b4b; margin-bottom: 1.5rem;">
                🔴  Recommendations for improvement  
            </h2>

            @if($recommendations && count($recommendations) > 0)
                @foreach($recommendations as $rec)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;">
                    <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                        <!-- رقم -->
                        <div style="width: 40px; height: 40px; background: #ef4444; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; flex-shrink: 0;">
                            {{ $loop->iteration }}
                        </div>
                        
                        <!-- السؤال -->
                        <div style="flex: 1;">
                            <p style="font-size: 14px; font-weight: 600; color: #1e1b4b; margin: 0 0 4px;">
                                {{ substr($rec['question'], 0, 80) }}{{ strlen($rec['question']) > 80 ? '...' : '' }}
                            </p>
                            
                        </div>
                    </div>

                    <!-- المقارنة -->
                    <div style="background: #f9fafb; border-radius: 8px; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="text-align: center; border-right: 1px solid #e5e7eb;">
                            <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px;">إجابتك ❌</p>
                            <p style="font-size: 20px; font-weight: 700; color: #ef4444; margin: 0;">
                                {{ $rec['your_answer'] }}
                            </p>
                        </div>
                        
                        <div style="text-align: center;">
                            <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px;">الإجابة الأفضل ✅</p>
                            <p style="font-size: 20px; font-weight: 700; color: #10b981; margin: 0;">
                                {{ $rec['recommended_answer'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; text-align: center;">
                    <p style="font-size: 16px; color: #6b7280; margin: 0;">لم نتمكن من جلب التوصيات. حاول مرة أخرى.</p>
                </div>
            @endif
        </div>

        <!-- أزرار الإجراء -->
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('quizzes.index') }}" style="background: #6366f1; color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                ⚡  Try again
            </a>
            
            <a href="{{ route('dashboard') }}" style="background: white; color: #4b5563; border: 1px solid #d1d5db; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                ←  Dashboard
            </a>
        </div>
    </div>
</div>
@endsection