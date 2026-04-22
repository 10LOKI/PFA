@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-[var(--neon-magenta)] font-mono space-y-1 mt-2']) }}>
        @foreach ((array) $messages as $message)
            <li class="text-glow-magenta">{{ $message }}</li>
        @endforeach
    </ul>
@endif
