<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-skew inline-flex items-center justify-center px-6 py-3 bg-transparent border-2 border-[var(--chrome-text)] text-[var(--chrome-text)] font-bold text-xs uppercase tracking-widest hover:bg-[var(--chrome-text)] hover:text-[var(--void-bg)] focus:outline-none focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-50 transition-all duration-200']) }}>
    <span>{{ $slot }}</span>
</button>
