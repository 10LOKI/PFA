<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-magenta)] uppercase tracking-wider drop-shadow-[0_0_15px_#FF00FF]">
            <span class="text-glow-magenta">LEADERBOARD</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Stats & Filters --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider">TOP VOLUNTEERS</h3>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-1">Rankings based on total points balance. Real-time updates.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('leaderboard.index') }}" class="btn-skew px-6 py-3 border-2 {{ !request()->routeIs('leaderboard.weekly') ? 'border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black' : 'border-[var(--chrome-text)]/30 text-[var(--chrome-text)]' }} font-bold text-xs uppercase tracking-widest transition-all">
                            <span>NATIONAL</span>
                        </a>
                        <a href="{{ route('leaderboard.weekly') }}" class="btn-skew px-6 py-3 border-2 {{ request()->routeIs('leaderboard.weekly') ? 'border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black' : 'border-[var(--chrome-text)]/30 text-[var(--chrome-text)]' }} font-bold text-xs uppercase tracking-widest transition-all">
                            <span>THIS WEEK</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Leaderboard Table --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] overflow-hidden relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-[var(--neon-cyan)]/30">
                                    <th class="px-4 py-3 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Grade</th>
                                    <th class="px-4 py-3 text-right text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Points</th>
                                    <th class="px-4 py-3 text-right text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Hours</th>
                                    @if(!isset($city) && !isset($establishmentId))
                                        <th class="px-4 py-3 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">City</th>
                                    @endif
                                    @if(!isset($establishmentId))
                                        <th class="px-4 py-3 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Establishment</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--neon-cyan)]/20">
                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="{{ 6 + (isset($city) ? 0 : 1) + (isset($establishmentId) ? 0 : 1) }}" class="px-4 py-12 text-center">
                                            <p class="text-xl font-heading text-[var(--neon-magenta)]">NO DATA AVAILABLE</p>
                                            <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-2">Start participating in events to climb the ranks!</p>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($users as $user)
                                        <tr class="hover:bg-[rgba(0,255,255,0.05)] transition-colors">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center">
                                                        <span class="font-heading font-bold text-[var(--neon-cyan)]">{{ $user->rank }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="h-10 w-10 rounded-full border-2 border-[var(--neon-magenta)]" alt="{{ $user->name }}">
                                                    @else
                                                        <div class="h-10 w-10 bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center">
                                                            <span class="font-heading font-bold text-[var(--neon-magenta)] text-sm">{{ strtoupper($user->name[0] ?? '?') }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-heading font-bold text-[var(--chrome-text)]">{{ $user->name }}</p>
                                                        <p class="text-xs font-mono text-[var(--chrome-text)]/60">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                                <span class="px-3 py-1 border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono text-xs uppercase tracking-wider">
                                                    {{ $user->grade }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                                <p class="font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_5px_rgba(0,255,255,0.5)]">{{ number_format($user->points_balance) }}</p>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap text-right font-mono text-[var(--chrome-text)]">
                                                {{ number_format($user->total_hours) }}h
                                            </td>

                                            @if(!isset($city) && !isset($establishmentId))
                                                <td class="px-4 py-4 whitespace-nowrap text-left font-mono text-[var(--chrome-text)]">
                                                    {{ $user->city ?? 'N/A' }}
                                                </td>
                                            @endif

                                            @if(!isset($establishmentId))
                                                <td class="px-4 py-4 whitespace-nowrap text-left font-mono text-[var(--chrome-text)]">
                                                    @if($user->establishment)
                                                        {{ $user->establishment->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
