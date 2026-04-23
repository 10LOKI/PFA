<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">PARTNER DASHBOARD</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- KYC Warning --}}
            @if(! auth()->user()->kyc_verified)
                <div class="glass-panel border-2 border-[var(--neon-orange)] p-6 relative">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-orange)]"></div>
                    <div class="flex items-start gap-4">
                        <span class="text-3xl animate-pulse">⚠️</span>
                        <div>
                            <p class="font-heading font-bold text-[var(--neon-orange)] mb-2 uppercase tracking-wider">KYC VERIFICATION PENDING</p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/80">Your account is awaiting verification. You cannot publish events until approved by an administrator.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Events --}}
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">📋</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
                                {{ auth()->user()->hostedEvents()->count() }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Total Missions</p>
                        </div>
                    </div>
                </div>

                {{-- Active Events --}}
                <div class="glass-panel border-2 border-[var(--neon-magenta)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(255,0,255,0.1)] border-2 border-[var(--neon-magenta)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">✅</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-magenta)] drop-shadow-[0_0_10px_#FF00FF]">
                                {{ auth()->user()->hostedEvents()->where('status', 'approved')->count() }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Active Missions</p>
                        </div>
                    </div>
                </div>

                {{-- Pending Events --}}
                <div class="glass-panel border-2 border-[var(--neon-orange)] p-6 relative group hover:border-[var(--neon-cyan)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-cyan)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(255,153,0,0.1)] border-2 border-[var(--neon-orange)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">⏳</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-orange)] drop-shadow-[0_0_10px_#FF9900]">
                                {{ auth()->user()->hostedEvents()->where('status', 'pending')->count() }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Pending Review</p>
                        </div>
                    </div>
                </div>

                {{-- Total Registrations --}}
                <div class="glass-panel border-2 border-[var(--neon-cyan)] p-6 relative group hover:border-[var(--neon-magenta)] transition-all duration-300">
                    <div class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-[var(--neon-magenta)] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[rgba(0,255,255,0.1)] border-2 border-[var(--neon-cyan)] rounded-full flex items-center justify-center">
                            <span class="text-3xl">👥</span>
                        </div>
                        <div>
                            <p class="text-4xl font-heading font-black text-[var(--neon-cyan)] drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
                                {{ auth()->user()->hostedEvents()->withCount('participants')->get()->sum('participants_count') }}
                            </p>
                            <p class="text-sm font-mono text-[var(--chrome-text)]/70 uppercase tracking-wider">Total Registrations</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <h3 class="text-2xl font-heading font-bold text-[var(--neon-magenta)] mb-6 uppercase tracking-wider drop-shadow-[0_0_5px_#FF00FF]">QUICK ACTIONS</h3>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('events.create') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-sm uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                        <span>⚡ CREATE MISSION</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-cyan)] text-[var(--neon-cyan)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-cyan)] hover:text-black transition-all duration-200">
                        <span>📂 MY EVENTS</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-magenta)] text-[var(--neon-magenta)] font-bold text-sm uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                        <span>🔄 REFRESH</span>
                    </a>
                    <a href="{{ route('rewards.create') }}" class="px-6 py-3 bg-[#FFDCDC] text-[#D4A574] border-2 border-[#FFDCDC] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        + Ajouter une récompense
                    </a>
                    <a href="{{ route('rewards.index') }}" class="px-6 py-3 bg-white text-[#D4A574] border-2 border-[#D4A574] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        Mes récompenses
                    </a>
                    <a href="{{ route('events.index') }}" class="px-6 py-3 bg-[#FFDCDC] text-[#D4A574] border-2 border-[#FFDCDC] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        Gérer événements
                    </a>
                </div>
            </div>

            {{-- Partner Capabilities --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vos capacités en tant que partenaire</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <h4 class="font-medium text-[#D4A574]">Gestion des événements</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ Créer et publier des missions de bénévolat</li>
                            <li>✓ Générer des QR codes pour le check-in</li>
                            <li>✓ Valider la participation des étudiants</li>
                            <li>✓ Noter et commenter les étudiants</li>
                        </ul>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-medium text-[#D4A574]">Récompenses & Marketplace</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ Ajouter des récompenses au marketplace</li>
                            <li>✓ Définir les coûts en points</li>
                            <li>✓ Gérer le stock et les conditions d'accès</li>
                        </ul>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-medium text-[#D4A574]">Analyse & Impact</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ Suivre les heures de bénévolat collectées</li>
                            <li>✓ Analyser l'impact social de vos missions</li>
                            <li>✓ Générer des rapports CSR</li>
                        </ul>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-medium text-[#D4A574]">Permissions spéciales</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ Accès au dashboard partenaire</li>
                            <li>✓ Approbation automatique (si certifié)</li>
                            <li>✓ Notifications push pour nouvelles inscriptions</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Active Events for Check-in --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Événements actifs - Scanner QR</h3>
                @if(auth()->user()->hostedEvents()->where('status', 'approved')->where('starts_at', '<=', now())->where('ends_at', '>=', now())->exists())
                    <div class="space-y-4">
                        @foreach(auth()->user()->hostedEvents()->where('status', 'approved')->where('starts_at', '<=', now())->where('ends_at', '>=', now())->get() as $event)
                            <div class="flex items-center justify-between p-4 bg-[#FFF2EB] rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $event->participants()->count() }}/{{ $event->volunteer_quota }} participants</p>
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg text-sm hover:bg-[#c49560] transition">
                                    Voir QR & Gérer
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun événement actif en ce moment.</p>
                @endif
            </div>

            {{-- Recent Events --}}
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider">RECENT MISSIONS</h3>
                    <a href="{{ route('events.index') }}" class="text-sm font-mono text-[var(--neon-magenta)] hover:text-[var(--neon-cyan)] uppercase tracking-wider transition-colors">
                        VIEW ALL →
                    </a>
                </div>

                @php
                    $recentEvents = auth()->user()->hostedEvents()->latest()->take(5)->get();
                @endphp

                @if($recentEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentEvents as $event)
                            <div class="flex items-center justify-between p-4 bg-[rgba(26,16,60,0.4)] border border-[var(--border-default)] hover:border-[var(--neon-cyan)] transition-all duration-300 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-[rgba(0,255,255,0.1)] border border-[var(--neon-cyan)] flex items-center justify-center">
                                        @if($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                                        @else
                                            <span class="text-xl">📅</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-heading font-bold text-[var(--chrome-text)] group-hover:text-[var(--neon-cyan)] transition-colors">
                                            {{ $event->title }}
                                        </h4>
                                        <p class="text-xs font-mono text-[var(--chrome-text)]/60">
                                            {{ $event->city }} • {{ $event->starts_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    @php
                                        $statusClass = match($event->status) {
                                            'approved' => 'border-green-400 text-green-400',
                                            'pending' => 'border-yellow-400 text-yellow-400',
                                            'rejected' => 'border-red-400 text-red-400',
                                            'cancelled' => 'border-gray-500 text-gray-500',
                                            default => 'border-gray-500 text-gray-500'
                                        };
                                        $statusLabel = strtoupper($event->status);
                                    @endphp
                                    <span class="px-3 py-1 border-2 {{ $statusClass }} font-mono text-xs uppercase tracking-widest">
                                        {{ $statusLabel }}
                                    </span>
                                    <a href="{{ route('events.show', $event) }}" class="text-sm text-[var(--neon-cyan)] hover:text-[var(--neon-magenta)] transition-colors font-mono uppercase tracking-wider">
                                        VIEW →
                                    </a>
                                    @can('update', $event)
                                        <a href="{{ route('events.edit', $event) }}" class="text-sm text-[var(--neon-cyan)] hover:text-[var(--neon-magenta)] transition-colors font-mono uppercase tracking-wider">
                                            ✎ EDIT
                                        </a>
                                    @endcan
                                    @can('delete', $event)
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, '{{ $event->title }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-400 transition-colors font-mono uppercase tracking-wider">
                                                🗑 CANCEL
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-4xl mb-4">📭</p>
                        <p class="text-xl font-heading text-[var(--neon-magenta)] mb-2">NO MISSIONS YET</p>
                        <p class="text-sm font-mono text-[var(--chrome-text)]/60">Create your first mission to get started.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
