@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-heading font-bold text-sm text-[var(--neon-cyan)] uppercase tracking-wider mb-2']) }}>
    {{ $value ?? $slot }}
</label>
