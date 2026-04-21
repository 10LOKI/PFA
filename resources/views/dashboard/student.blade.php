<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-heading font-black text-[#00FFFF] text-glow-cyan uppercase tracking-wider">
            > OPERATOR TERMINAL
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Status LED Bar --}}
            <div class="flex justify-center">
                <div class="glass-panel border-2 border-[var(--neon-cyan)] px-8 py-4 flex items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-[var(--neon-magenta)] rounded-full animate-pulse shadow-[var(--glow-magenta)]"></div>
                        <span class="text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)]">SYSTEM ONLINE</span>
                    </div>
                    <div class="w-px h-6 bg-[var(--neon-cyan)]"></div>
                    <div class="text-sm font-mono text-[var(--neon-cyan)] uppercase tracking-widest">ID: {{ auth()->id() }}</div>
                    <div class="w-px h-6 bg-[var(--neon-cyan)]"></div>
                    <div class="text-sm font-mono text-[var(--neon-orange)] uppercase tracking-widest">RANK: {{ strtoupper(auth()->user()->grade) }}</div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Points Card --}}
                <div class="glass-panel border-t-2 border-t-[var(--neon-magenta)] border-l-4 border-l-[var(--neon-cyan)] p-6 relative group hover:shadow-[var(--glow-magenta)] transition-all duration-300">
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 border-2 border-[var(--neon-magenta)] flex items-center justify-center bg-[rgba(255,0,255,0.1)]">
                            <span class="text-2xl">🎯</span>
                        </div>
                        <div>
                            <p class="text-3xl font-heading font-black text-[var(--neon-magenta)]" style="text-shadow: 0 0 10px #FF00FF;">{{ number_format(auth()->user()->points_balance) }}</p>
                            <p class="text-xs font-mono uppercase tracking-widest text-[var(--chrome-text)] opacity-60">POINTS</p>
                        </div>
                    </div>
                </div>

                {{-- Hours Card --}}
                <div class="glass-panel border-t-2 border-t-[var(--neon-cyan)] border-l-4 border-l-[var(--neon-magenta)] p-6 relative group hover:shadow-[var(--glow-cyan)] transition-all duration-300">
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 border-2 border-[var(--neon-cyan)] flex items-center justify-center bg-[rgba(0,255,255,0.1)]">
                            <span class="text-2xl">⏱️</span>
                        </div>
                        <div>
                            <p class="text-3xl font-heading font-black text-[var(--neon-cyan)]" style="text-shadow: 0 0 10px #00FFFF;">{{ auth()->user()->total_hours }}<span class="text-lg">H</span></p>
                            <p class="text-xs font-mono uppercase tracking-widest text-[var(--chrome-text)]/60">HOURS</p>
                        </div>
                    </div>
                </div>

                {{-- Grade Card --}}
                <div class="glass-panel border-t-2 border-t-[var(--neon-orange)] border-l-4 border-l-[var(--neon-cyan)] p-6 relative group hover:shadow-[var(--glow-orange)] transition-all duration-300">
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-orange)]"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-orange)]"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 border-2 border-[var(--neon-orange)] flex items-center justify-center bg-[rgba(255,153,0,0.1)]">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <div>
                            <p class="text-2xl font-heading font-black text-[var(--neon-orange)] uppercase" style="text-shadow: 0 0 10px #FF9900;">{{ auth()->user()->grade }}</p>
                            <p class="text-xs font-mono uppercase tracking-widest text-[var(--chrome-text)]/60">RANK</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grade Progress --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-1 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>
                <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] mb-4 uppercase tracking-wider">PROGRESSION</h3>
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
                    <div class="h-3 bg-[var(--void-bg)] border border-[var(--neon-magenta)]/30 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[var(--neon-magenta)] to-[var(--neon-cyan)] rounded-full transition-all duration-1000 ease-out animate-pulse" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs font-mono text-[var(--neon-cyan)] mt-3 uppercase tracking-widest">{{ $currentHours }}H → {{ $nextThreshold }}H TO {{ strtoupper($nextGrade) }}</p>
                @else
                    <div class="h-3 bg-gradient-to-r from-[var(--neon-magenta)] to-[var(--neon-cyan)] rounded-full shadow-[var(--glow-magenta)]"></div>
                    <p class="text-sm font-mono text-[var(--neon-orange)] mt-3 uppercase tracking-widest">MAX RANK ACHIEVED</p>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>
                <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] mb-6 uppercase tracking-wider">ACTIONS</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('events.index') }}" class="btn-skew px-6 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_20px_#00FFFF] transition-all duration-200">
                        <span>📅 BROWSE EVENTS</span>
                    </a>
                    <a href="{{ route('rewards.index') }}" class="btn-skew px-6 py-4 border-2 border-[var(--neon-magenta)] bg-transparent text-[var(--neon-magenta)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black shadow-[var(--glow-magenta)] transition-all duration-200">
                        <span>🎁 REWARDS</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-skew px-6 py-4 border-2 border-[var(--neon-orange)] bg-transparent text-[var(--neon-orange)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-orange)] hover:text-black shadow-[var(--glow-orange)] transition-all duration-200">
                        <span>📋 MY REGISTRATIONS</span>
                    </a>
                    <a href="{{ route('wallet.index') }}" class="btn-skew px-6 py-4 border-2 border-[var(--neon-cyan)] bg-transparent text-[var(--neon-cyan)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black shadow-[var(--glow-cyan)] transition-all duration-200">
                        <span>💳 WALLET</span>
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <h3 class="text-xl font-heading font-bold text-[var(--neon-magenta)] mb-6 uppercase tracking-wider">RECENT ACTIVITY</h3>
                <div class="space-y-4">
                    @php
                        $registrations = \App\Models\EventUser::where('user_id', auth()->id())->with('event')->latest()->take(3)->get();
                    @endphp
                    @if($registrations->count() > 0)
                        @foreach($registrations as $reg)
                            <div class="flex items-center justify-between p-4 bg-[rgba(9,0,20,0.6)] border border-[var(--neon-cyan)]/30">
                                <div>
                                    <p class="font-mono text-sm text-[var(--chrome-text)]">{{ $reg->event->title ?? 'Unknown Event' }}</p>
                                    <p class="text-xs text-[var(--chrome-text)]/60 mt-1">{{ $reg->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-mono text-[var(--neon-cyan)]">{{ strtoupper($reg->status) }}</p>
                                    @if($reg->checked_in_at)
                                        <p class="text-xs text-[var(--neon-orange)]">+{{ $reg->points_earned }} PTS</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60 text-center py-8">NO RECENT ACTIVITY DETECTED</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
