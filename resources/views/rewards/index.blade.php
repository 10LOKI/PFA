<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-heading font-black text-[var(--neon-magenta)] text-glow-magenta uppercase tracking-wider">
            > MARKETPLACE
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Wallet Panel --}}
            <div class="glass-panel border-t-2 border-t-[var(--neon-orange)] border-r-4 border-r-[var(--neon-magenta)] p-6 mb-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <p class="text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)]/60 mb-1">> WALLET_BALANCE</p>
                    <p class="text-5xl font-heading font-black text-[var(--neon-orange)] drop-shadow-[0_0_15px_#FF9900]">{{ number_format(auth()->user()->points_balance) }} <span class="text-2xl">PTS</span></p>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)]/60 mb-1">> OPERATOR_RANK</p>
                    <p class="text-3xl font-heading font-bold text-[var(--neon-cyan)] uppercase drop-shadow-[0_0_10px_#00FFFF]">{{ auth()->user()->grade }}</p>
                    <p class="text-xs font-mono text-[var(--neon-cyan)] mt-1">NEXT_RANK: {{ \App\Actions\User\UpgradeGradeAction::getNextThreshold(auth()->user()->grade) ?? 'MAX' }}H</p>
                </div>
                </div>
            </div>

            {{-- Rewards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($rewards as $reward)
                    <div class="glass-panel border-t-2 border-t-[var(--neon-magenta)] border-l-4 border-l-[var(--neon-cyan)] overflow-hidden relative group hover:shadow-[var(--glow-magenta)] transition-all duration-300 flex flex-col">
                        <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-[var(--neon-orange)]"></div>
                        <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-[var(--neon-orange)]"></div>
                        
                        {{-- Image --}}
                        @if($reward->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $reward->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $reward->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-[var(--void-bg)] to-transparent opacity-80"></div>
                                @if($reward->is_premium)
                                    <div class="absolute top-4 right-4 px-3 py-1 bg-[var(--neon-orange)] text-black font-bold text-xs uppercase tracking-widest border-2 border-black">
                                        PREMIUM
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="w-full h-48 bg-[var(--void-bg)] flex items-center justify-center border-b-2 border-[var(--neon-cyan)]/30">
                                <span class="text-6xl animate-pulse" style="text-shadow: 0 0 20px #FF00FF;">🎁</span>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg font-heading font-bold text-[var(--chrome-text)] mb-2 leading-tight">{{ $reward->title }}</h3>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60 mb-4 line-clamp-2">{{ $reward->description }}</p>

                            {{-- Stock indicator --}}
                            @if($reward->stock !== null)
                                <div class="mb-4">
                                    <div class="flex justify-between text-xs font-mono mb-1">
                                        <span class="text-[var(--chrome-text)]/60">INVENTORY</span>
                                        <span class="<?= $reward->stock <= 5 ? 'text-[var(--neon-orange)]' : 'text-[var(--neon-cyan)]' ?>">{{ $reward->stock }} REMAINING</span>
                                    </div>
                                    <div class="h-2 bg-[var(--void-bg)] border border-[var(--neon-cyan)]/30 rounded-full overflow-hidden">
                                        @php
                                            $stockPercent = max(0, min(100, ($reward->stock / 10) * 100));
                                        @endphp
                                        <div class="h-full bg-gradient-to-r from-[var(--neon-cyan)] to-[var(--neon-magenta)] transition-all duration-500" style="width: <?= $stockPercent ?>%"></div>
                                    </div>
                                </div>
                            @endif

                            {{-- Grade requirement --}}
                            @if($reward->min_grade)
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 text-xs font-mono">
                                        <span class="text-[var(--chrome-text)]/60">REQUIRED_RANK:</span>
                                        <span class="text-[var(--neon-magenta)] uppercase">{{ strtoupper($reward->min_grade) }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-heading font-black text-[var(--neon-orange)]" style="text-shadow: 0 0 10px #FF9900;">{{ $reward->points_cost }} <span class="text-sm">PTS</span></span>
                                </div>

                                @if(auth()->user()->can('reward.redeem'))
                                    @if($reward->isAccessibleBy(auth()->user()))
                                        @if(auth()->user()->points_balance >= $reward->points_cost)
                                            <form action="{{ route('rewards.redeem', $reward) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-skew w-full px-6 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest hover:shadow-[var(--glow-cyan)] transition-all duration-200">
                                                    <span>⚡ REDEEM NOW</span>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-full px-6 py-4 border-2 border-gray-600 text-gray-600 font-mono uppercase tracking-widest cursor-not-allowed bg-[rgba(107,114,128,0.1)]">
                                                <span>INSUFFICIENT FUNDS</span>
                                            </button>
                                        @endif
                                    @else
                                        <button disabled class="w-full px-6 py-4 border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono uppercase tracking-widest cursor-not-allowed bg-[rgba(255,153,0,0.1)]">
                                            <span>RANK LOCKED</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="glass-panel border-2 border-[var(--neon-orange)] p-12 text-center">
                            <p class="text-2xl font-heading text-[var(--neon-orange)]" style="text-shadow: 0 0 10px #FF9900;">MARKETPLACE EMPTY</p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-2">CHECK BACK LATER FOR NEW REWARDS</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>