@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-[rgba(26,16,60,0.6)] border border-[var(--border-default)] text-[var(--chrome-text)] font-mono px-4 py-3 rounded-md focus:outline-none focus:border-[var(--neon-cyan)] focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-30 transition-all duration-200 placeholder:text-[var(--chrome-text)] placeholder:opacity-50']) }}>
