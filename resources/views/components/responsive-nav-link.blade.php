@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 border-l-4 border-[var(--neon-cyan)] text-start text-base font-bold font-mono uppercase tracking-wider text-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)] focus:outline-none transition-all duration-200'
            : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent text-start text-base font-mono uppercase tracking-wider text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.05)] hover:border-l-[var(--neon-magenta)] focus:outline-none transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
