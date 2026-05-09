<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center items-center px-4 py-4 bg-[#6366f1] border border-transparent rounded-2xl font-black text-sm text-white uppercase tracking-widest hover:bg-[#4f46e5] active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 shadow-lg shadow-indigo-200']) }}>
    {{ $slot }}
</button>
