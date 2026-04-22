<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-skew w-full inline-flex items-center justify-center px-6 py-3 bg-transparent border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-bold font-mono uppercase tracking-wider hover:bg-[var(--neon-cyan)] hover:text-[var(--void-bg)] hover:shadow-[0_0_20px_rgba(0,255,255,0.8)] focus:outline-none focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-50 transition-all duration-200']) }}>
    <span>{{ $slot }}</span>
</button>
