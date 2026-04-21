<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-black text-3xl text-[#FF00FF] text-glow-magenta uppercase tracking-wider">
            {{ __('> ADMIN CONTROL CENTER') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-24">

            {{-- System Status --}}
            <div class="flex justify-center">
                <div class="flex items-center gap-2 glass-panel border border-[#00FFFF] px-4 py-2">
                    <div class="w-3 h-3 bg-[#00FFFF] rounded-full animate-pulse shadow-[0_0_10px_#00FFFF]"></div>
                    <span class="text-xs font-mono uppercase tracking-widest text-[#E0E0E0]/70">> SYSTEM NOMINAL</span>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="glass-panel border-2 border-[#00FFFF] p-8 hover:shadow-[0_0_20px_rgba(0,255,255,0.3)] transition-all duration-300 relative">
                    <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="text-center">
                        <p class="text-5xl font-heading font-black text-[#00FFFF] text-glow-cyan">{{ \App\Models\User::where('role', 'student')->count() }}</p>
                        <p class="text-sm font-mono uppercase tracking-wider text-[#E0E0E0]/70 mt-2">> STUDENTS</p>
                    </div>
                </div>
                <div class="glass-panel border-2 border-[#FF00FF] p-8 hover:shadow-[0_0_20px_rgba(255,0,255,0.3)] transition-all duration-300 relative">
                    <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                    <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                    <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                    <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                    <div class="text-center">
                        <p class="text-5xl font-heading font-black text-[#FF00FF] text-glow-magenta">{{ \App\Models\User::where('role', 'partner')->count() }}</p>
                        <p class="text-sm font-mono uppercase tracking-wider text-[#E0E0E0]/70 mt-2">> PARTNERS</p>
                    </div>
                </div>
                <div class="glass-panel border-2 border-[#FF9900] p-8 hover:shadow-[0_0_20px_rgba(255,153,0,0.3)] transition-all duration-300 relative">
                    <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#FF9900] rotate-45"></div>
                    <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#FF9900] rotate-45"></div>
                    <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#FF9900] rotate-45"></div>
                    <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#FF9900] rotate-45"></div>
                    <div class="text-center">
                        <p class="text-5xl font-heading font-black text-[#FF9900]">{{ \App\Models\Event::where('status', 'pending')->count() }}</p>
                        <p class="text-sm font-mono uppercase tracking-wider text-[#E0E0E0]/70 mt-2">> PENDING EVENTS</p>
                    </div>
                </div>
                <div class="glass-panel border-2 border-[#00FFFF] p-8 hover:shadow-[0_0_20px_rgba(0,255,255,0.3)] transition-all duration-300 relative">
                    <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                    <div class="text-center">
                        <p class="text-5xl font-heading font-black text-[#00FFFF] text-glow-cyan">{{ \App\Models\Partner::where('kyc_status', 'pending')->count() }}</p>
                        <p class="text-sm font-mono uppercase tracking-wider text-[#E0E0E0]/70 mt-2">> KYC PENDING</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-panel border-2 border-[#FF00FF] p-8 relative">
                <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#FF00FF] rotate-45"></div>
                <h3 class="text-2xl font-heading font-bold text-[#FF00FF] text-glow-magenta mb-6 uppercase tracking-wide">> System Controls</h3>
                <div class="flex flex-wrap gap-6">
                    <a href="{{ route('admin.kyc.index') }}" class="btn-skew border-2 border-[#00FFFF] bg-[#00FFFF] text-black px-8 py-4 font-mono uppercase tracking-wider hover:opacity-80 hover:shadow-[0_0_20px_#00FFFF]">
                        <span>KYC VERIFICATION</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-skew border-2 border-[#FF00FF] bg-transparent text-[#FF00FF] px-8 py-4 font-mono uppercase tracking-wider hover:bg-[#FF00FF] hover:text-black">
                        <span>ALL EVENTS</span>
                    </a>
                </div>
            </div>

            {{-- Pending Events --}}
            <div class="glass-panel border-2 border-[#00FFFF] p-8 relative">
                <div class="absolute top-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                <div class="absolute top-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                <div class="absolute bottom-3 left-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                <div class="absolute bottom-3 right-3 w-2 h-2 rounded-none bg-[#00FFFF] rotate-45"></div>
                <h3 class="text-2xl font-heading font-bold text-[#00FFFF] text-glow-cyan mb-6 uppercase tracking-wide">> Pending Events Queue</h3>
                @php
                    $pendingEvents = \App\Models\Event::where('status', 'pending')->with('partner')->latest()->get();
                @endphp

                @if($pendingEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm font-mono">
                            <thead class="glass-panel border-b-2 border-[#00FFFF]">
                                <tr>
                                    <th class="px-4 py-3 text-left font-mono uppercase tracking-widest text-[#00FFFF]">> TITLE</th>
                                    <th class="px-4 py-3 text-left font-mono uppercase tracking-widest text-[#00FFFF]">> PARTNER</th>
                                    <th class="px-4 py-3 text-left font-mono uppercase tracking-widest text-[#00FFFF]">> DATE</th>
                                    <th class="px-4 py-3 text-left font-mono uppercase tracking-widest text-[#00FFFF]">> CITY</th>
                                    <th class="px-4 py-3 text-left font-mono uppercase tracking-widest text-[#00FFFF]">> POINTS</th>
                                    <th class="px-4 py-3 text-center font-mono uppercase tracking-widest text-[#00FFFF]">> ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#2D1B4E]">
                                @foreach($pendingEvents as $event)
                                    <tr class="hover:bg-[rgba(0,255,255,0.05)] transition-colors duration-200">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('events.show', $event) }}" class="text-[#FF00FF] hover:text-[#00FFFF] font-medium font-mono uppercase tracking-widest transition-colors">
                                                {{ $event->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-[#E0E0E0]/70 font-mono">{{ $event->partner->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-[#E0E0E0]/70 font-mono">{{ $event->starts_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-[#E0E0E0]/70 font-mono">{{ $event->city }}</td>
                                        <td class="px-4 py-3 text-[#E0E0E0]/70 font-mono">{{ $event->points_reward }} PTS</td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-center gap-2">
                                                <form action="{{ route('events.approve', $event) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-4 py-2 bg-[#00FFFF] text-black font-mono uppercase tracking-widest hover:opacity-80 transition-all duration-200">
                                                        APPROVE
                                                    </button>
                                                </form>
                                                <form action="{{ route('events.reject', $event) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-4 py-2 bg-[#FF00FF] text-white font-mono uppercase tracking-widest hover:opacity-80 transition-all duration-200">
                                                        REJECT
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-[#E0E0E0]/70 text-center py-8 font-mono uppercase tracking-widest">> NO PENDING EVENTS</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>