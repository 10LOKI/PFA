<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">ADMIN DASHBOARD</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Students --}}
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">🎓</span>
                        </div>
                        <p class="text-4xl font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
                            {{ \App\Models\User::where('role', 'student')->count() }}
                        </p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Students</p>
                    </div>
                </div>

                {{-- Partners --}}
                <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">🤝</span>
                        </div>
                        <p class="text-4xl font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">
                            {{ \App\Models\User::where('role', 'partner')->count() }}
                        </p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Partners</p>
                    </div>
                </div>

                {{-- Pending Events --}}
                <div class="glass-panel border-2 border-[var(--neon-orange)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-[rgba(255,153,0,0.1)] border-2 border-[var(--neon-orange)] rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">⏳</span>
                        </div>
                        <p class="text-4xl font-heading font-black text-[var(--neon-orange)] drop-shadow-[0_0_10px_#FF9900]">
                            {{ \App\Models\Event::where('status', 'pending')->count() }}
                        </p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Pending Events</p>
                    </div>
                </div>

                {{-- Pending KYC --}}
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">🔍</span>
                        </div>
                        <p class="text-4xl font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
                            {{ \App\Models\Partner::where('kyc_status', 'pending')->count() }}
                        </p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">KYC Pending</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <h3 class="text-2xl font-heading font-bold text-[var(--neon-magenta)] mb-6 uppercase tracking-wider drop-shadow-[0_0_5px_#FF00FF]">QUICK ACTIONS</h3>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.kyc.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                        <span>🔍 KYC VERIFICATION</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                        <span>📂 ALL EVENTS</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all duration-200">
                        <span>🔄 REFRESH</span>
                    </a>
                </div>
            </div>

            {{-- Pending Events --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider">PENDING EVENTS</h3>
                    <a href="{{ route('events.index') }}" class="text-sm font-mono text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                        VIEW ALL →
                    </a>
                </div>

                @php
                    $pendingEvents = \App\Models\Event::where('status', 'pending')->latest()->take(5)->get();
                @endphp

                @if($pendingEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingEvents as $event)
                            <div class="flex items-center justify-between p-4 bg-[rgba(26,16,60,0.4)] border border-[var(--border-default)] hover:border-[var(--neon-orange)] transition-all duration-300 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-[rgba(255,153,0,0.1)] border-2 border-[var(--neon-orange)] flex items-center justify-center">
                                        @if($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                                        @else
                                            <span class="text-xl">📅</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-heading font-bold text-[var(--chrome-text)] group-hover:text-[var(--neon-orange)] transition-colors">
                                            {{ $event->title }}
                                        </h4>
                                        <p class="text-xs font-mono text-[var(--chrome-text)]/60">
                                            {{ $event->city }} • {{ $event->partner->name ?? 'Unknown Partner' }} • {{ $event->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 border-2 border-yellow-400 text-yellow-400 font-mono text-xs uppercase tracking-widest">
                                        PENDING
                                    </span>
                                    <div class="flex gap-2">
                                        <form action="{{ route('events.approve', $event) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 border-2 border-green-500 text-green-500 font-mono text-xs uppercase tracking-widest hover:bg-green-500 hover:text-black transition-all duration-200">
                                                ✓ APPROVE
                                            </button>
                                        </form>
                                        <form action="{{ route('events.reject', $event) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 border-2 border-red-500 text-red-500 font-mono text-xs uppercase tracking-widest hover:bg-red-500 hover:text-black transition-all duration-200">
                                                ✕ REJECT
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-4xl mb-4">✅</p>
                        <p class="text-xl font-heading text-[var(--neon-cyan)] mb-2">NO PENDING APPROVALS</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">All events have been reviewed.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
