@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-[var(--neon-cyan)] text-sm font-bold font-mono uppercase tracking-wider text-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)] focus:outline-none transition-all duration-200'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-mono uppercase tracking-wider text-[var(--chrome-text)] hover:text-[var(--neon-magenta)] hover:bg-[rgba(255,0,255,0.05)] focus:outline-none transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
