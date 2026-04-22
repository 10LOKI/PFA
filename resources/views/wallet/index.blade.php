<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">WALLET & POINTS HISTORY</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Balance Card --}}
                <div class="lg:col-span-1">
                    <div class="glass-panel border-2 border-[var(--neon-orange)] p-6 relative sticky top-6">
                        <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                        <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                        <div class="mb-6">
                            <p class="text-sm font-mono uppercase tracking-widest text-[var(--chrome-text)]/60 mb-2">> WALLET_BALANCE</p>
                            <p class="text-6xl font-heading font-black text-[var(--neon-orange)] drop-shadow-[0_0_20px_#FF9900]">
                                {{ number_format($user->points_balance) }} <span class="text-2xl">PTS</span>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[rgba(34,197,94,0.1)] border-2 border-green-500 rounded-lg p-4 text-center">
                                <p class="text-xs font-mono text-green-400 uppercase tracking-wider mb-1">Earned</p>
                                <p class="text-2xl font-heading font-black text-green-400">{{ number_format($totalEarned) }}</p>
                            </div>
                            <div class="bg-[rgba(239,68,68,0.1)] border-2 border-red-500 rounded-lg p-4 text-center">
                                <p class="text-xs font-mono text-red-400 uppercase tracking-wider mb-1">Spent</p>
                                <p class="text-2xl font-heading font-black text-red-400">{{ number_format($totalSpent) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="lg:col-span-1">
                    <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative h-fit">
                        <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                        <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                        <h3 class="text-xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider mb-6">FILTERS</h3>

                        <form method="GET" action="{{ route('wallet.index') }}" class="space-y-6">
                            <div>
                                <x-input-label for="type" :value="__('Type')" />
                                <select id="type" name="type" class="block w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:border-[var(--neon-magenta)] focus:ring-2 focus:ring-[var(--neon-magenta)] focus:ring-opacity-30 transition-all duration-200">
                                    <option value="">All Types</option>
                                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned from Events</option>
                                    <option value="spent" {{ request('type') === 'spent' ? 'selected' : '' }}>Spent on Rewards</option>
                                    <option value="burned" {{ request('type') === 'burned' ? 'selected' : '' }}>Points Burned</option>
                                    <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-3 pt-4">
                                <button type="submit" class="btn-skew w-full px-6 py-3 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)]">
                                    <span>APPLY</span>
                                </button>
                                <a href="{{ route('wallet.index') }}" class="text-center text-sm text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                                    RESET
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Transaction History --}}
                <div class="lg:col-span-2">
                    <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative">
                        <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                        <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-heading font-bold text-[var(--neon-magenta)] uppercase tracking-wider">TRANSACTION HISTORY</h3>
                            @if($transactions->count() > 0)
                                <span class="text-sm font-mono text-[var(--chrome-text)]/60">{{ $transactions->total() }} transactions</span>
                            @endif
                        </div>

                        @if($transactions->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-4xl mb-4">💸</p>
                                <p class="text-xl font-heading text-[var(--neon-magenta)] mb-2">NO TRANSACTIONS</p>
                                <p class="text-sm font-mono text-[var(--chrome-text)]/60">Start participating in events to earn points!</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($transactions as $transaction)
                                    <div class="p-4 bg-[rgba(26,16,60,0.4)] border-l-4 {{ $transaction->amount > 0 ? 'border-[var(--neon-cyan)]' : 'border-red-500' }} transition-all duration-200 hover:bg-[rgba(26,16,60,0.6)]">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-heading font-bold text-[var(--chrome-text)]">{{ $transaction->description }}</p>
                                                <p class="text-xs font-mono text-[var(--chrome-text)]/60">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                            <div class="text-right space-x-3 font-mono">
                                                @if($transaction->amount > 0)
                                                    <span class="text-lg text-[var(--neon-cyan)] font-bold">+{{ number_format($transaction->amount) }} pts</span>
                                                    <span class="text-xs text-[var(--neon-cyan)]/60">Bal: {{ number_format($transaction->balance_after) }}</span>
                                                @else
                                                    <span class="text-lg text-red-500 font-bold">{{ number_format($transaction->amount) }} pts</span>
                                                    <span class="text-xs text-red-500/60">Bal: {{ number_format($transaction->balance_after) }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Source info --}}
                                        @if($transaction->source)
                                            <div class="mt-2 text-xs font-mono text-[var(--chrome-text)]/50">
                                                {{ class_basename($transaction->source) }}
                                                @if($transaction->source_type === 'event')
                                                    - {{ $transaction->source->title }}
                                                @elseif($transaction->source_type === 'reward_redemption')
                                                    - {{ $transaction->source->reward->title ?? 'Reward' }}
                                                @endif
                                            </div>
                                        @elseif($transaction->source_type && $transaction->source_id)
                                            <div class="mt-2 text-xs font-mono text-[var(--chrome-text)]/50">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->source_type)) }} #{{ $transaction->source_id }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
