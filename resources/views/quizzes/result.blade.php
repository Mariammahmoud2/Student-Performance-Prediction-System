@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <div class="bg-white p-10 rounded-3xl shadow-xl border border-indigo-50">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-chart-line text-[#6366f1] text-3xl"></i>
            </div>
            
            <h1 class="text-3xl font-black text-gray-900 mb-4">Performance Analysis</h1>
            <p class="text-gray-500 mb-8 font-medium text-lg">Based on your 10 answers, our AI model predicts:</p>

            <div class="inline-block px-12 py-6 bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] rounded-2xl shadow-lg">
                <span class="text-white text-4xl font-black uppercase tracking-widest">
                    {{ $prediction }}
                </span>
            </div>

            <div class="mt-12">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-[#6366f1] font-bold transition-colors">
                    <i class="fas fa-arrow-left ml-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection