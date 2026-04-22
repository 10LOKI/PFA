<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">NOTIFICATIONS</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-magenta)] overflow-hidden relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>

                @if($notifications->count() > 0)
                    <div class="p-6 border-b border-[var(--neon-magenta)]/30 flex justify-between items-center">
                        <span class="text-sm font-mono text-[var(--chrome-text)]/60">{{ $notifications->count() }} pending alerts</span>
                        <button onclick="markAllRead()" class="text-sm text-[var(--neon-cyan)] hover:text-[var(--neon-magenta)] uppercase tracking-wider font-bold transition-colors">
                            > MARK ALL READ
                        </button>
                    </div>
                @endif

                <div class="divide-y divide-[var(--neon-magenta)]/20">
                    @forelse($notifications as $notification)
                        <div class="p-6 hover:bg-[rgba(255,0,0,0.05)] transition-all duration-200 {{ $notification->read ? '' : 'bg-[rgba(0,255,255,0.05)]' }}">
                            <div class="flex items-start gap-4">
                                <div class="text-3xl shrink-0">🔔</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <h4 class="font-heading font-bold text-[var(--chrome-text)]">{{ $notification->title }}</h4>
                                        @if(!$notification->read)
                                            <span class="px-2 py-0.5 bg-[var(--neon-magenta)] text-black font-bold text-xs uppercase tracking-wider animate-pulse">NEW</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-mono text-[var(--chrome-text)]/70 mb-2">{{ $notification->message }}</p>
                                    <p class="text-xs font-mono text-[var(--chrome-text)]/40">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex flex-col gap-2 shrink-0">
                                    @if($notification->link)
                                        <a href="{{ $notification->link }}" class="btn-skew px-4 py-2 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-mono text-xs uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all">
                                            <span>VIEW</span>
                                        </a>
                                    @endif
                                    @if(!$notification->read)
                                        <button onclick="markRead({{ $notification->id }})" class="text-xs text-[var(--neon-magenta)] hover:text-[var(--neon-orange)] uppercase tracking-wider font-mono">
                                            Mark read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-6xl mb-6">🔔</p>
                            <p class="text-xl font-heading text-[var(--chrome-text)] mb-2">NO NOTIFICATIONS</p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60">You're all caught up!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function markRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }

        function markAllRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    </script>
</x-app-layout>
