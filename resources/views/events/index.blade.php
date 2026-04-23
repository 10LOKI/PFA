<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">VOLUNTEERING MISSIONS</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Sidebar Filters --}}
                <div class="lg:col-span-1">
                    <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 sticky top-6">
                        <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                        <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                        <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] mb-6 uppercase tracking-wider">FILTERS</h3>

                        <form method="GET" action="{{ route('events.index') }}" class="space-y-6">
                            {{-- Search --}}
                            <div>
                                <label for="search" class="block text-sm font-mono uppercase tracking-wider text-[var(--neon-magenta)] mb-2">> SEARCH</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Keywords..."
                                    class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-magenta)]/50 focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300">
                            </div>

                            {{-- City --}}
                            <div>
                                <label for="city" class="block text-sm font-mono uppercase tracking-wider text-[var(--neon-magenta)] mb-2">> CITY</label>
                                <input type="text" name="city" id="city" value="{{ request('city') }}"
                                    placeholder="City name..."
                                    class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300">
                            </div>

                            {{-- Interests (Categories) --}}
                            <div>
                                <label class="block text-sm font-mono uppercase tracking-wider text-[var(--neon-magenta)] mb-2">> CATEGORIES</label>
                                <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach($categories as $category)
                                        @php
                                            $currentInterests = old('interests', request('interests', []));
                                            $selected = is_array($currentInterests) && in_array($category, $currentInterests);
                                        @endphp
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="interests[]" value="{{ $category }}"
                                                {{ $selected ? 'checked' : '' }}
                                                class="rounded border-[var(--border-default)] bg-[rgba(26,16,60,0.6)] text-[var(--neon-cyan)] focus:ring-[var(--neon-cyan)]">
                                            <span class="text-sm text-[var(--chrome-text)] group-hover:text-[var(--neon-cyan)] transition-colors">{{ $category }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-[var(--chrome-text)]/60 mt-2 font-mono">SELECT ONE OR MORE</p>
                            </div>

                            <div class="flex flex-col gap-3 pt-4 border-t border-[var(--neon-cyan)]/30">
                                <button type="submit" class="w-full btn-skew px-6 py-3 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_20px_#00FFFF] transition-all duration-200">
                                    <span>APPLY</span>
                                </button>
                                <a href="{{ route('events.index') }}" class="text-center text-sm text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                                    RESET ALL
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Events Grid --}}
                <div class="lg:col-span-3">
                    @if(auth()->user()->isPartner() && auth()->user()->hostedEvents()->count() > 0)
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="my-events-only"
                                           {{ request()->has('mine') ? 'checked' : '' }}
                                           class="rounded border-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)] text-[var(--neon-cyan)] focus:ring-[var(--neon-cyan)]">
                                    <span class="text-sm font-mono text-[var(--chrome-text)]">MY EVENTS ONLY</span>
                                </label>
                                <span class="text-xs font-mono text-[var(--chrome-text)]/60">
                                    ({{ auth()->user()->hostedEvents()->count() }} total)
                                </span>
                            </div>
                            <a href="{{ route('events.index') }}" class="text-sm text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                                RESET
                            </a>
                        </div>
                        <script>
                            document.getElementById('my-events-only').addEventListener('change', function(e) {
                                const url = new URL(window.location.href);
                                if (e.target.checked) {
                                    url.searchParams.set('mine', '1');
                                } else {
                                    url.searchParams.delete('mine');
                                }
                                window.location.href = url.toString();
                            });
                        </script>
                    @endif

                    @if(request()->hasAny(['search', 'city', 'interests', 'mine']))
                        <div class="mb-6 flex items-center justify-between pb-4 border-b border-[var(--neon-cyan)]/30">
                            <p class="text-sm font-mono text-[var(--chrome-text)]">
                                Filtered results
                            </p>
                            <a href="{{ route('events.index') }}" class="text-sm text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors flex items-center gap-2">
                                <span>✕</span> CLEAR ALL FILTERS
                            </a>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($events as $event)
                            @php
                                $isOwned = auth()->user()->id === $event->partner_id;
                                $isCancelled = $event->status === 'cancelled';
                                $cardClass = $isCancelled ? 'opacity-60 grayscale' : '';
                                $borderClass = match($event->status) {
                                    'pending' => 'border-yellow-400/50',
                                    'approved' => $isOwned ? 'border-[var(--neon-cyan)]' : 'border-green-400/50',
                                    'rejected' => 'border-red-400/50',
                                    'cancelled' => 'border-gray-500/50',
                                    default => 'border-[var(--neon-cyan)]'
                                };
                            @endphp

                            <div class="glass-panel border-2 {{ $borderClass }} overflow-hidden relative group hover:border-[var(--neon-magenta)] transition-all duration-300 {{ $cardClass }}">
                                <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                                {{-- Image --}}
                                @if($event->image)
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $event->title }}">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--void-bg)] via-transparent to-transparent"></div>
                                    </div>
                                @else
                                    <div class="w-full h-48 bg-[var(--void-bg)] border-b border-[var(--neon-cyan)]/30 flex items-center justify-center">
                                        <span class="text-4xl">🎯</span>
                                    </div>
                                @endif

                                <div class="p-6">
                                    {{-- Header: Category, Status & Points --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="px-3 py-1 border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono text-xs uppercase tracking-widest bg-[rgba(255,153,0,0.1)]">
                                            {{ $event->category ?? 'GENERAL' }}
                                        </span>
                                        <div class="flex items-center gap-3">
                                            @if($event->status === 'pending')
                                                <span class="px-2 py-1 border border-yellow-400 text-yellow-400 font-mono text-[10px] uppercase tracking-widest bg-[rgba(255,193,7,0.1)]">PENDING</span>
                                            @elseif($event->status === 'approved')
                                                <span class="px-2 py-1 border border-green-400 text-green-400 font-mono text-[10px] uppercase tracking-widest bg-[rgba(34,197,94,0.1)]">APPROVED</span>
                                            @elseif($event->status === 'rejected')
                                                <span class="px-2 py-1 border border-red-400 text-red-400 font-mono text-[10px] uppercase tracking-widest bg-[rgba(239,68,68,0.1)]">REJECTED</span>
                                            @elseif($event->status === 'cancelled')
                                                <span class="px-2 py-1 border border-gray-500 text-gray-500 font-mono text-[10px] uppercase tracking-widest bg-[rgba(107,114,128,0.1)]">CANCELLED</span>
                                            @endif
                                            <span class="text-lg font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">
                                                {{ $event->effectivePoints() }} <span class="text-sm">PTS</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="text-xl font-heading font-bold text-[var(--chrome-text)] mb-2 {{ $isCancelled ? 'line-through' : 'hover:text-[var(--neon-cyan)]' }} transition-colors">
                                        {{ $event->title }}
                                        @if($isCancelled)
                                            <span class="text-xs font-mono text-red-400 ml-2">(CANCELLED)</span>
                                        @endif
                                    </h3>

                                    {{-- Location & Date --}}
                                    <div class="flex flex-col gap-2 mb-4 text-sm font-mono text-[var(--chrome-text)]/70">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[var(--neon-cyan)]">📍</span>
                                            <span>{{ $event->city }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[var(--neon-orange)]">⏱</span>
                                            <span>{{ $event->starts_at->format('d M Y - H:i') }}</span>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-4">
                                        @php
                                            $percentage = ($event->participants()->count() / $event->volunteer_quota) * 100;
                                        @endphp
                                        <div class="flex justify-between text-xs font-mono text-[var(--chrome-text)]/60 mb-2">
                                            <span>VOLUNTEERS</span>
                                            <span>{{ $event->participants()->count() }}/{{ $event->volunteer_quota }}</span>
                                        </div>
                                        <div class="w-full h-2 bg-[var(--void-bg)] border border-[var(--border-default)] overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-[var(--neon-magenta)] via-[var(--neon-orange)] to-[var(--neon-cyan)] transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="space-y-2">
                                        <a href="{{ route('events.show', $event) }}" class="block w-full text-center btn-skew px-4 py-3 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-xs uppercase tracking-wider hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                                            <span>VIEW DETAILS</span>
                                        </a>

                                        @if(!$isCancelled)
                                            @can('update', $event)
                                                <a href="{{ route('events.edit', $event) }}" class="block w-full text-center px-3 py-2 border border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-mono text-[10px] uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all duration-200">
                                                    <span>✎ EDIT MISSION</span>
                                                </a>
                                            @endcan

                                            @can('delete', $event)
                                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, '{{ $event->title }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full px-3 py-2 border border-red-500 text-red-500 font-mono text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-200">
                                                        <span>🗑 CANCEL MISSION</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            <div class="text-center py-2">
                                                <span class="text-xs font-mono text-gray-500">MISSION CANCELLED</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full glass-panel border-2 border-[var(--neon-magenta)] p-12 text-center">
                                <p class="text-xl font-heading text-[var(--neon-magenta)] mb-2">NO MISSIONS FOUND</p>
                                <p class="text-sm font-mono text-[var(--chrome-text)]/60">
                                    @if(auth()->user()->isPartner())
                                        <a href="{{ route('events.create') }}" class="text-[var(--neon-cyan)] hover:underline">Create your first mission</a> to get started.
                                    @else
                                        No events match your current filters.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($events->hasPages())
                        <div class="mt-8">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
