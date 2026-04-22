<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">OPERATOR DASHBOARD</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Points Balance --}}
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">🎯</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
                                {{ auth()->user()->points_balance }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Points Balance</p>
                        </div>
                    </div>
                </div>

                {{-- Certified Hours --}}
                <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">⏱️</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">
                                {{ auth()->user()->total_hours }}<span class="text-2xl">h</span>
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Certified Hours</p>
                        </div>
                    </div>
                </div>

                {{-- Current Grade --}}
                <div class="glass-panel border-2 border-[var(--neon-orange)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(255,153,0,0.1)] border-2 border-[var(--neon-orange)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">⭐</span>
                        </div>
                        <div>
                            <p class="text-2xl font-heading font-black text-[var(--neon-orange)] drop-shadow-[0_0_10px_#FF9900] uppercase">
                                {{ auth()->user()->grade }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Current Grade</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grade Progress --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <h3 class="text-2xl font-heading font-bold text-[var(--neon-magenta)] mb-6 uppercase tracking-wider drop-shadow-[0_0_5px_#FF00FF]">GRADE PROGRESSION</h3>

                @php
                    $thresholds = ['novice' => 0, 'pilier' => 50, 'ambassadeur' => 150];
                    $currentHours = auth()->user()->total_hours;
                    $nextGrade = null;
                    $nextThreshold = null;
                    $progress = 0;

                    foreach ($thresholds as $grade => $threshold) {
                        if ($threshold > $currentHours) {
                            $nextGrade = $grade;
                            $nextThreshold = $threshold;
                            $progress = min(100, ($currentHours / $threshold) * 100);
                            break;
                        }
                    }
                @endphp

                @if($nextGrade)
                    <div class="mb-4">
                        <div class="flex justify-between text-sm font-mono text-[var(--chrome-text)] mb-2">
                            <span>CURRENT: {{ $currentHours }}H</span>
                            <span class="text-[var(--neon-cyan)]">NEXT: {{ strtoupper($nextGrade) }} ({{ $nextThreshold }}H)</span>
                        </div>
                        <div class="w-full h-4 bg-[var(--void-bg)] border-2 border-[var(--border-default)] overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[var(--neon-magenta)] via-[var(--neon-orange)] to-[var(--neon-cyan)] transition-all duration-700" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-2xl font-heading text-[var(--neon-cyan)] mb-2">🎉 MAXIMUM GRADE ACHIEVED</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">You've reached the highest rank, Operator.</p>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <h3 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider mb-6">Quick Actions</h3>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('events.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                        <span>🚀 FIND MISSIONS</span>
                    </a>
                    <a href="{{ route('rewards.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                        <span>🎁 REWARDS</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-skew px-8 py-4 border-2 border-[var(--chrome-text)]/30 text-[var(--chrome-text)] font-bold text-sm uppercase tracking-widest hover:border-[var(--neon-cyan)] hover:text-[var(--neon-cyan)] transition-all duration-200">
                        <span>🔄 REFRESH</span>
                    </a>
                </div>
            </div>

            {{-- Recent Events --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider">RECENT MISSIONS</h3>
                    <a href="{{ route('events.index') }}" class="text-sm font-mono text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                        VIEW ALL →
                    </a>
                </div>

                @php
                    $recentRegistrations = auth()->user()->events()->latest()->take(5)->get();
                @endphp

                @if($recentRegistrations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRegistrations as $event)
                            <div class="flex items-center justify-between p-4 bg-[rgba(26,16,60,0.4)] border border-[var(--border-default)] hover:border-[var(--neon-cyan)] transition-all duration-300 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-[rgba(0,255,255,0.1)] border border-[var(--neon-cyan)] flex items-center justify-center">
                                        @if($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                                        @else
                                            <span class="text-xl">📅</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-heading font-bold text-[var(--chrome-text)] group-hover:text-[var(--neon-cyan)] transition-colors">
                                            {{ $event->title }}
                                        </h4>
                                        <p class="text-xs font-mono text-[var(--chrome-text)]/60">
                                            {{ $event->city }} • {{ $event->starts_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                @php
                                    $status = $event->pivot->checked_in_at ? 'checked-in' : 'registered';
                                    $statusClass = match($status) {
                                        'checked-in' => 'border-green-400 text-green-400',
                                        'registered' => 'border-[var(--neon-cyan)] text-[var(--neon-cyan)]',
                                        default => 'border-gray-500 text-gray-500'
                                    };
                                    $statusLabel = $status === 'checked-in' ? 'CHECKED-IN' : 'REGISTERED';
                                @endphp
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 border-2 {{ $statusClass }} font-mono text-xs uppercase tracking-widest">
                                        {{ $statusLabel }}
                                    </span>
                                    <a href="{{ route('events.show', $event) }}" class="text-sm text-[var(--neon-cyan)] hover:text-[var(--neon-magenta)] transition-colors font-mono uppercase tracking-wider">
                                        VIEW →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-4xl mb-4">📭</p>
                        <p class="text-xl font-heading text-[var(--neon-magenta)] mb-2">NO MISSION HISTORY</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">Start by registering for an event.</p>
                        <a href="{{ route('events.index') }}" class="inline-block mt-4 btn-skew px-6 py-3 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-bold text-xs uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all duration-200">
                            <span>BROWSE MISSIONS</span>
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
