<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">COMMS NETWORK</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-cyan)] overflow-hidden relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>

                {{-- Header --}}
                <div class="p-6 border-b border-[var(--neon-cyan)]/30 flex items-center justify-between">
                    <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider">ACTIVE CONNECTIONS</h3>
                    <a href="{{ route('users.index') }}" class="btn-skew px-6 py-3 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-xs uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                        <span>+ NEW CONTACT</span>
                    </a>
                </div>

                {{-- Conversations List --}}
                <div class="divide-y divide-[var(--neon-cyan)]/20">
                    @forelse($conversations as $conversation)
                        @php
                            $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                            $lastMessage = $conversation->messages->first();
                            $unread = $conversation->messages()
                                ->where('sender_id', '!=', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                        @endphp

                        <a href="{{ route('messages.show', $conversation) }}" class="block p-6 hover:bg-[rgba(0,255,255,0.05)] transition-all duration-200 group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center">
                                    <span class="text-lg font-heading font-bold text-[var(--neon-magenta)]">
                                        {{ strtoupper($other->name[0] ?? '?') }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-heading font-bold text-[var(--chrome-text)] group-hover:text-[var(--neon-cyan)] transition-colors">{{ $other->name }}</p>
                                        @if($lastMessage)
                                            <span class="text-xs font-mono text-[var(--chrome-text)]/60">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-mono text-[var(--chrome-text)]/70 truncate">
                                        {{ $lastMessage->body ?? 'No messages' }}
                                    </p>
                                </div>
                                @if($unread > 0)
                                    <span class="px-3 py-1 bg-[var(--neon-magenta)] text-black font-bold text-xs uppercase tracking-wider animate-pulse">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center">
                            <p class="text-xl font-heading text-[var(--neon-magenta)]">NO SIGNALS DETECTED</p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-2">No active connections found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
