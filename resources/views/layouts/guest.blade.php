<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduPRE - Authentication</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex" style="background: linear-gradient(135deg, #6b8dd6 0%, #7c6ed6 40%, #9b6bbf 100%);">

        {{-- الجانب الأيسر - اللوجو والاسم --}}
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center relative overflow-hidden">
            {{-- دوائر زخرفية --}}
            <div class="absolute top-[-60px] left-[-60px] w-64 h-64 rounded-full" style="background: rgba(255,255,255,0.08);"></div>
            <div class="absolute bottom-[-40px] left-[10%] w-48 h-48 rounded-full" style="background: rgba(255,255,255,0.06);"></div>

            <div class="relative z-10 text-center">
                {{-- اللوجو --}}
                <div class="mb-6 inline-block p-6 rounded-[28px]" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(10px);">
                    <img src="{{ asset('images/logo.png') }}" alt="EduPRE" class="w-28 h-28 object-contain brightness-0 invert">
                </div>
                {{-- الاسم --}}
                <h1 class="text-5xl font-black text-white tracking-widest mt-4">EDUPRE</h1>
            </div>
        </div>

        {{-- الجانب الأيمن - الكارت --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">

                {{-- اللوجو للموبايل --}}
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" class="w-16 h-16 brightness-0 invert" alt="EduPRE">
                </div>

                {{-- الكارت --}}
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                    {{-- هيدر الكارت --}}
                    <div class="py-6 px-8 text-center" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                        <h2 class="text-2xl font-bold text-white">Login</h2>
                        <p class="text-white/80 text-sm mt-1">Welcome back! Please login to your account</p>
                    </div>

                    {{-- بودي الكارت --}}
                    <div class="px-8 py-8 bg-gray-50">
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>
</html>