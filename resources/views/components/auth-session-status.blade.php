@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-mono text-sm text-[var(--neon-cyan)] text-glow-cyan mb-4']) }}>
        {{ $status }}
    </div>
@endif
