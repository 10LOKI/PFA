<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-orange)] uppercase tracking-wider drop-shadow-[0_0_15px_#FF9900]">
            <span class="text-glow-orange">KYC VERIFICATION</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-orange)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-heading font-bold text-[var(--neon-orange)] uppercase tracking-wider">PENDING VERIFICATIONS</h3>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60 mt-1">Review partner submissions and approve or reject.</p>
                    </div>
                    <div class="px-6 py-3 bg-[rgba(255,153,0,0.1)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono text-sm uppercase tracking-widest">
                        {{ $pending->count() }} PENDING
                    </div>
                </div>

                @if($pending->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-[var(--neon-cyan)]/30">
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Company</th>
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Contact</th>
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Phone</th>
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">City</th>
                                    <th class="px-6 py-4 text-left text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Created</th>
                                    <th class="px-6 py-4 text-center text-xs font-mono uppercase tracking-wider text-[var(--neon-cyan)]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--neon-orange)]/30">
                                @foreach($pending as $partner)
                                    <tr class="hover:bg-[rgba(255,153,0,0.05)] transition-colors">
                                        <td class="px-6 py-4 font-heading font-bold text-[var(--chrome-text)]">
                                            {{ $partner->company_name }}
                                        </td>
                                        <td class="px-6 py-4 text-[var(--chrome-text)] font-mono">
                                            {{ $partner->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-[var(--chrome-text)]/70 font-mono">
                                            {{ $partner->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-[var(--chrome-text)] font-mono">
                                            {{ $partner->phone }}
                                        </td>
                                        <td class="px-6 py-4 text-[var(--chrome-text)] font-mono">
                                            {{ $partner->city }}
                                        </td>
                                        <td class="px-6 py-4 text-[var(--chrome-text)]/60 font-mono">
                                            {{ $partner->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-3">
                                                <form action="{{ route('admin.kyc.approve', $partner) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-skew px-6 py-3 border-2 border-green-500 text-green-500 font-bold text-xs uppercase tracking-widest hover:bg-green-500 hover:text-black transition-all duration-200">
                                                        <span>✓ APPROVE</span>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.kyc.reject', $partner) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-skew px-6 py-3 border-2 border-red-500 text-red-500 font-bold text-xs uppercase tracking-widest hover:bg-red-500 hover:text-black transition-all duration-200">
                                                        <span>✕ REJECT</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $pending->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <p class="text-6xl mb-6">✅</p>
                        <p class="text-2xl font-heading text-[var(--neon-cyan)] mb-2">NO PENDING VERIFICATIONS</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">All partners have been reviewed.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
