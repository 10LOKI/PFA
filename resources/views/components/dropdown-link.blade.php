<a {{ $attributes->merge(['class' => 'block w-full px-4 py-3 text-start text-sm font-mono uppercase tracking-wider text-[var(--chrome-text)] hover:text-[var(--neon-cyan)] hover:bg-[rgba(0,255,255,0.1)] focus:outline-none transition-all duration-200']) }}>
    {{ $slot }}
</a>
