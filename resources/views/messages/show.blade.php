<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">COMMS LINK: {{ $conversation->participants->where('id', '!=', auth()->id())->first()->name ?? 'UNKNOWN' }}</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Link --}}
            <div class="mb-6">
                <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 text-sm font-mono text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                    <span>← BACK TO NETWORK</span>
                </a>
            </div>

            {{-- Chat Container --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] overflow-hidden relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                {{-- Messages Area --}}
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    @forelse($conversation->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] rounded-xl px-6 py-4 {{ $message->sender_id === auth()->id() 
                                ? 'bg-[var(--neon-magenta)] text-black border-2 border-[var(--neon-magenta)]' 
                                : 'bg-[rgba(26,16,60,0.6)] border-2 border-[var(--border-default)] text-[var(--chrome-text)]' }}">
                                <p class="font-mono">{{ $message->body }}</p>
                                <p class="text-xs mt-2 opacity-60 font-mono">
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-xl font-heading text-[var(--chrome-text)]/60">NO TRANSMISSIONS</p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/40 mt-2">Start the conversation.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Message Input --}}
                <div class="p-6 border-t border-[var(--neon-magenta)]/30 bg-[rgba(26,16,60,0.4)]">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $conversation->participants->where('id', '!=', auth()->id())->first()->id }}">
                        <input type="text" name="body" placeholder="Transmit message..." 
                               class="flex-1 px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono placeholder-[var(--chrome-text)]/50 focus:outline-none focus:border-[var(--neon-cyan)] focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-30 transition-all duration-200"
                               required>
                        <button type="submit" class="btn-skew px-8 py-3 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_20px_#00FFFF] transition-all duration-200 shrink-0">
                            <span>TRANSMIT</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script>
        const pusher = new Pusher('{{ config('services.pusher.key') }}', {
            cluster: '{{ config('services.pusher.cluster') }}',
            encrypted: true
        });

        const channel = pusher.subscribe('conversation.{{ $conversation->id }}');
        channel.bind('Illuminate\\Broadcasting\\MessageSent', function(data) {
            if (data.message.sender_id !== {{ auth()->id() }}) {
                location.reload();
            }
        });
    </script>
    @endpush
</x-app-layout>
