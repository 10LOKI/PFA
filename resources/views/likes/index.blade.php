<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-magenta)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(255,0,255,0.8)]">
            <span class="text-glow-magenta">LIKED MISSIONS</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($likedEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($likedEvents as $event)
                        <div class="glass-panel border-2 border-[var(--neon-magenta)] hover:border-[var(--neon-cyan)] transition-all duration-300 relative group">
                            <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                            @if($event->image)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $event->title }}">
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 border border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-mono text-xs uppercase tracking-widest">
                                        {{ $event->category ?? 'GENERAL' }}
                                    </span>
                                    <span class="text-2xl font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">
                                        {{ $event->effectivePoints() }} <span class="text-sm">PTS</span>
                                    </span>
                                </div>

                                <h3 class="text-xl font-heading font-bold text-[var(--chrome-text)] mb-3 leading-tight">
                                    {{ $event->title }}
                                </h3>

                                <p class="text-sm font-mono text-[var(--chrome-text)]/70 mb-4 line-clamp-2">
                                    {{ Str::limit($event->description, 100) }}
                                </p>

                                <div class="flex items-center justify-between text-xs font-mono text-[var(--chrome-text)]/60 mb-4">
                                    <span>📅 {{ $event->starts_at->format('d/m/Y') }}</span>
                                    <span>📍 {{ $event->city }}</span>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-[var(--neon-cyan)]/30">
                                    <a href="{{ route('events.show', $event) }}" class="text-[var(--neon-cyan)] hover:text-[var(--neon-orange)] transition-colors font-mono text-sm uppercase tracking-wider">
                                        VIEW DETAILS →
                                    </a>
                                    <form action="{{ route('events.unlike', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[var(--neon-magenta)] hover:text-red-500 transition-colors font-mono text-sm uppercase tracking-wider flex items-center gap-2">
                                            <span class="animate-pulse">❤️</span> UNLIKE
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-12 text-center">
                    <div class="text-6xl mb-6">💔</div>
                    <h3 class="text-2xl font-heading font-bold text-[var(--chrome-text)] mb-2">NO LIKED EVENTS YET</h3>
                    <p class="text-[var(--chrome-text)]/60 font-mono mb-6">Explore missions and save your favorites to see them here.</p>
                    <a href="{{ route('events.index') }}" class="inline-block btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold uppercase tracking-widest hover:shadow-[var(--glow-cyan)] transition-all">
                        BROWSE EVENTS
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
