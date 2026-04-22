<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">USER DATABASE</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Search Form --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <form action="{{ route('users.index') }}" method="get" class="flex flex-col sm:flex-row gap-4">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search ?? '' }}"
                        placeholder="Search by name or email..." 
                        class="flex-1 px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono placeholder-[var(--chrome-text)]/50 focus:outline-none focus:border-[var(--neon-cyan)] focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-30 transition-all duration-200"
                    >
                    <button type="submit" class="btn-skew px-8 py-3 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_20px_#00FFFF] transition-all duration-200 shrink-0">
                        <span>SEARCH</span>
                    </button>
                </form>
            </div>

            {{-- Users Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($users as $user)
                    <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                        <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center shrink-0">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-full object-cover" alt="{{ $user->name }}">
                                @else
                                    <span class="text-2xl font-heading font-bold text-[var(--neon-magenta)]">{{ strtoupper($user->name[0] ?? '?') }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-heading font-bold text-[var(--chrome-text)] text-lg truncate group-hover:text-[var(--neon-cyan)] transition-colors">
                                    {{ $user->name }}
                                </h4>
                                <p class="text-sm font-mono text-[var(--chrome-text)]/60 truncate">{{ $user->email }}</p>
                                @if($user->role)
                                    <span class="inline-block mt-2 px-3 py-1 border border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono text-xs uppercase tracking-wider">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-[var(--neon-cyan)]/30 flex justify-end">
                            <form action="{{ route('messages.start') }}" method="post" class="inline">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn-skew px-6 py-3 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-xs uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                                    <span>✉ SEND MESSAGE</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full glass-panel border-2 border-[var(--neon-cyan)] p-12 text-center">
                        <p class="text-2xl font-heading text-[var(--neon-magenta)] mb-2">NO USER FOUND</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">Try a different search query.</p>
                    </div>
                @endforelse
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
