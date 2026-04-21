<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">MISSION BRIEF</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image --}}
                    @if($event->image)
                        <div class="glass-panel border-2 border-[var(--neon-cyan)] p-2 relative">
                            <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>
                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-64 object-cover" alt="{{ $event->title }}">
                        </div>
                    @endif

                    {{-- Info Panel --}}
                    <div class="glass-panel border-t-2 border-t-[var(--neon-cyan)] border-l-4 border-l-[var(--neon-magenta)] p-8 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>
                        
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-4 py-2 border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono text-xs uppercase tracking-widest bg-[rgba(255,153,0,0.1)]">
                                {{ $event->category ?? 'GENERAL' }}
                            </span>
                            <div class="flex items-center gap-4">
                                @if($event->status === 'pending')
                                    <span class="px-4 py-2 border-2 border-yellow-400 text-yellow-400 font-mono text-xs uppercase tracking-widest bg-[rgba(255,193,7,0.1)]">PENDING</span>
                                @elseif($event->status === 'approved')
                                    <span class="px-4 py-2 border-2 border-green-400 text-green-400 font-mono text-xs uppercase tracking-widest bg-[rgba(34,197,94,0.1)]">APPROVED</span>
                                @elseif($event->status === 'rejected')
                                    <span class="px-4 py-2 border-2 border-red-400 text-red-400 font-mono text-xs uppercase tracking-widest bg-[rgba(239,68,68,0.1)]">REJECTED</span>
                                @elseif($event->status === 'cancelled')
                                    <span class="px-4 py-2 border-2 border-gray-500 text-gray-500 font-mono text-xs uppercase tracking-widest bg-[rgba(107,114,128,0.1)]">CANCELLED</span>
                                @endif
                                <span class="text-3xl font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">{{ $event->effectivePoints() }} <span class="text-lg">PTS</span></span>
                            </div>
                        </div>

                         <p class="text-[var(--chrome-text)] text-lg mb-6 leading-relaxed font-mono">{{ $event->description }}</p>

                         {{-- Action Buttons --}}
                         <div class="flex flex-wrap gap-4 mb-6 pt-6 border-t border-[var(--neon-cyan)]/30">
                            @if(auth()->user()->can('event.register'))
                                @if($event->isLikedBy(auth()->user()))
                                    <form action="{{ route('events.unlike', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-2 px-6 py-3 border-2 border-[var(--neon-magenta)] bg-[var(--neon-magenta)] text-black font-bold text-sm uppercase tracking-widest hover:bg-opacity-80 transition-all duration-200">
                                            <span>❤️ UNLIKE ({{ $event->likesCount }})</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('events.like', $event) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 px-6 py-3 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all duration-200">
                                            <span>🤍 LIKE ({{ $event->likesCount }})</span>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if(auth()->user()->can('event.generate-qr') && auth()->id() === $event->partner_id)
                                <a href="{{ route('events.qr', $event) }}" class="flex items-center gap-2 px-6 py-3 border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-orange)] hover:text-black transition-all duration-200">
                                    <span>📱 VIEW QR CODE</span>
                                </a>
                            @endif
                        </div>

                        {{-- Terminal Data Block --}}
                        <div class="bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] p-6 font-mono">
                            <div class="space-y-3">
                                <div class="flex justify-between border-b border-[var(--neon-cyan)]/30 pb-2">
                                    <span class="text-[var(--chrome-text)]/60">> LOCATION</span>
                                    <span class="text-[var(--neon-cyan)]">{{ $event->city }}, {{ $event->address }}</span>
                                </div>
                                <div class="flex justify-between border-b border-[var(--neon-cyan)]/30 pb-2">
                                    <span class="text-[var(--chrome-text)]/60">> START_TIME</span>
                                    <span class="text-[var(--neon-cyan)]">{{ $event->starts_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between border-b border-[var(--neon-cyan)]/30 pb-2">
                                    <span class="text-[var(--chrome-text)]/60">> DURATION</span>
                                    <span class="text-[var(--neon-cyan)]">{{ $event->duration_hours }} HOURS</span>
                                </div>
                                <div class="flex justify-between border-b border-[var(--neon-cyan)]/30 pb-2">
                                    <span class="text-[var(--chrome-text)]/60">> VOLUNTEER_SLOTS</span>
                                    <span class="text-[var(--neon-cyan)]">{{ $event->participants()->count() }} / {{ $event->volunteer_quota }}</span>
                                </div>
                                <div class="flex justify-between border-b border-[var(--neon-cyan)]/30 pb-2">
                                    <span class="text-[var(--chrome-text)]/60">> MISSION_ID</span>
                                    <span class="text-[var(--neon-cyan)]">#{{ $event->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[var(--chrome-text)]/60">> PARTNER_ID</span>
                                    <span class="text-[var(--neon-cyan)]">#{{ $event->partner_id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Comments section --}}
                    @if($event->comments->count() > 0)
                        <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6">
                            <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] mb-6 uppercase tracking-wider">COMMENTS</h3>
                            @foreach($event->comments as $comment)
                                <div class="border-b border-[var(--neon-cyan)]/20 py-4 last:border-b-0">
                                    <p class="text-[var(--chrome-text)] text-sm mb-2">{{ $comment->body }}</p>
                                    <div class="flex items-center gap-2 text-xs font-mono text-[var(--chrome-text)]/60">
                                        <span class="text-[var(--neon-magenta)]">{{ $comment->user->name }}</span>
                                        <span>•</span>
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    {{-- Partner QR (only for event owner) --}}
                    @if(auth()->user()->can('event.generate-qr') && auth()->id() === $event->partner_id)
                        <div class="glass-panel border-2 border-[var(--neon-orange)] p-8 text-center relative">
                            <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-orange)]"></div>
                            <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-orange)]"></div>
                            <h3 class="text-xl font-heading font-bold text-[var(--neon-orange)] mb-6 uppercase tracking-wider">CHECK-IN TERMINAL</h3>
                            <div class="bg-white p-6 inline-block rounded-lg mb-4 shadow-[var(--glow-orange)]">
                                <img src="{{ route('events.qr', $event) }}" alt="QR Code" class="w-48 h-48">
                            </div>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60 mb-6">SCAN TO VALIDATE STUDENT PRESENCE</p>

                            {{-- Check-in form --}}
                            <form action="{{ route('events.checkin', $event) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="text" name="qr_token" placeholder="[ENTER QR TOKEN]" 
                                       class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--neon-cyan)] font-mono placeholder-[var(--neon-magenta)]/50 focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all"
                                       required>
                                <select name="student_id" class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--neon-cyan)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all" required>
                                    <option value="">[ SELECT STUDENT ]</option>
                                    @foreach($event->participants()->wherePivot('status', 'registered')->get() as $participant)
                                        <option value="{{ $participant->id }}">{{ strtoupper($participant->name) }} ({{ $participant->grade }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full btn-skew px-6 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest hover:shadow-[var(--glow-cyan)] transition-all duration-200">
                                    <span>CONFIRM CHECK-IN</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Registration / Status --}}
                    <div class="glass-panel border-l-4 border-l-[var(--neon-magenta)] p-8 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>
                        @php
                            $isRegistered = $event->participants()->where('user_id', auth()->id())->exists();
                            $pivot = $isRegistered ? $event->participants()->where('user_id', auth()->id())->first()->pivot : null;
                        @endphp

                        @if($isRegistered)
                            <div class="text-center">
                                @if($pivot->checked_in_at)
                                    <div class="border-2 border-green-500 bg-[rgba(34,197,94,0.1)] p-6 mb-6">
                                        <p class="text-2xl font-heading text-green-400 drop-shadow-[0_0_10px_#22c55e] mb-2">✅ ACCESS GRANTED</p>
                                        <p class="text-sm font-mono text-[var(--neon-cyan)]">{{ $pivot->points_earned }} POINTS CREDITED</p>
                                    </div>
                                @else
                                    <div class="border-2 border-[var(--neon-orange)] bg-[rgba(255,153,0,0.1)] p-6 mb-6">
                                        <p class="text-2xl font-heading text-[var(--neon-orange)] drop-shadow-[0_0_10px_#FF9900] mb-2">📋 AWAITING CHECK-IN</p>
                                        <p class="text-sm font-mono text-[var(--chrome-text)]">PRESENT AT VENUE AND SCAN QR</p>
                                    </div>
                                @endif
                                <form action="{{ route('events.unregister', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-6 py-3 border-2 border-red-500 text-red-500 font-mono uppercase tracking-widest hover:bg-red-500 hover:text-black transition-all duration-200">
                                        🚫 CANCEL REGISTRATION
                                    </button>
                                </form>
                            </div>
                        @elseif(!$event->isFull() && auth()->user()->can('event.register'))
                            <form action="{{ route('events.register', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full btn-skew px-6 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-lg uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF]">
                                    <span>🔒 JOIN MISSION</span>
                                </button>
                            </form>
                        @elseif($event->isFull())
                            <div class="text-center py-8">
                                <p class="text-xl font-heading text-[var(--neon-orange)] drop-shadow-[0_0_10px_#FF9900]">MISSION CAPACITY REACHED</p>
                                <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-2">ALL SLOTS FILLED</p>
                            </div>
                        @endif
                    </div>

                    {{-- Participants Preview --}}
                    <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                        <h3 class="text-xl font-heading font-bold text-[var(--neon-magenta)] mb-4 uppercase tracking-wider drop-shadow-[0_0_5px_#FF00FF]">OPERATORS ({{ $event->participants->count() }})</h3>
                        
                        @if($event->participants->count() > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($event->participants->take(8) as $participant)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-[rgba(255,0,255,0.1)] border border-[var(--neon-magenta)]/30">
                                        <div class="w-8 h-8 rounded-full bg-[var(--neon-magenta)] flex items-center justify-center">
                                            <span class="text-sm font-bold text-black">{{ strtoupper($participant->name[0]) }}</span>
                                        </div>
                                        <div class="text-xs font-mono">
                                            <p class="text-[var(--chrome-text)]">{{ strtoupper($participant->name) }}</p>
                                            <p class="text-[var(--neon-cyan)]/60">{{ strtoupper($participant->grade) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60">NO OPERATORS REGISTERED</p>
                        @endif
                    </div>

                    {{-- Likes Social Proof --}}
                    @if($event->likesCount > 0)
                        <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6">
                            <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] mb-4 uppercase tracking-wider">LIKES</h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl text-[var(--neon-magenta)] animate-pulse">❤️</span>
                                    <span class="text-2xl font-heading text-[var(--neon-magenta)]">{{ $event->likesCount }}</span>
                                </div>
                                <div class="flex -space-x-2">
                                    @foreach($event->likedBy->take(5) as $liker)
                                        <div class="w-10 h-10 rounded-full border-2 border-[var(--neon-magenta)] bg-[rgba(255,0,255,0.2)] flex items-center justify-center text-xs font-bold text-[var(--neon-magenta)]" title="{{ $liker->name }}">
                                            {{ strtoupper($liker->name[0]) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>