<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- اللوجو -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center p-1.5">
                    <img src="{{ asset('images/logo.png') }}" alt="EduPRE" class="w-full h-full object-contain">
                </div>
                <span class="text-lg font-black text-[#1a1c3d] tracking-tight">
                    Edu<span class="text-[#6366f1]">PRE</span>
                </span>
            </div>

            <!-- الروابط -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-[#6366f1] text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fas fa-home text-xs"></i> Dashboard
                </a>
                <a href="{{ route('quizzes.index') }}" 
                   class="flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('quizzes.*') ? 'bg-[#6366f1] text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fas fa-list text-xs"></i> Quizzes
                </a>
                <a href="{{ route('grades.index') }}" 
                   class="flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('grades.*') ? 'bg-[#6366f1] text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fas fa-chart-bar text-xs"></i> Grades
                </a>
            </div>

            <!-- اليمين: User + Logout -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#6366f1] rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <span class="text-gray-700 font-bold text-sm hidden md:block">
                    {{ Auth::user()->name ?? 'Guest' }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>