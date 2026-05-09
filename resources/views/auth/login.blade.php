<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username Field --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
            <div class="flex items-center border border-gray-200 rounded-lg bg-[#f0f2ff] px-3 py-3 gap-3 focus-within:border-[#6366f1] transition-all">
                <i class="fas fa-user text-gray-400 text-sm shrink-0"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       placeholder="Enter your username"
                       class="flex-1 bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none text-gray-700 text-sm placeholder-gray-400 p-0"
                       required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password Field --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <div class="flex items-center border border-gray-200 rounded-lg bg-[#f0f2ff] px-3 py-3 gap-3 focus-within:border-[#6366f1] transition-all">
                <i class="fas fa-lock text-gray-400 text-sm shrink-0"></i>
                <input id="password" type="password" name="password"
                       placeholder="Enter your password"
                       class="flex-1 bg-transparent border-none outline-none ring-0 focus:ring-0 focus:outline-none text-gray-700 text-sm placeholder-gray-400 p-0"
                       required autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Remember Me + Forgot Password --}}
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-[#6366f1] shadow-sm focus:ring-0 focus:ring-offset-0">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
           
        </div>

        {{-- Login Button --}}
        <button type="submit"
                class="w-full py-3 rounded-lg text-white font-bold text-base tracking-wide transition-all active:scale-95 shadow-lg"
                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            Login
        </button>

        {{-- Sign Up --}}
        <p class="text-center text-sm text-gray-500 mt-5">
            Not a member?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: #6366f1;">Sign up</a>
        </p>

        

    </form>
</x-guest-layout>