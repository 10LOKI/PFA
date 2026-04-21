<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Partenaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- KYC Warning --}}
            @if(! auth()->user()->kyc_verified)
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg p-4">
                    <p class="font-medium">⚠️ Votre compte est en attente de vérification KYC.</p>
                    <p class="text-sm mt-1">Vous ne pouvez pas publier d'événements avant approbation par un administrateur.</p>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFDCDC]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#FFE8CD] rounded-full flex items-center justify-center">
                            <span class="text-2xl">📋</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#D4A574]">{{ auth()->user()->hostedEvents()->count() }}</p>
                            <p class="text-sm text-gray-500">Total Événements</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFE8CD]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#FFD6BA] rounded-full flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#D4A574]">{{ auth()->user()->hostedEvents()->where('status', 'approved')->count() }}</p>
                            <p class="text-sm text-gray-500">Événements Actifs</p>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Quick Actions --}}
            <div class="bg-[#FFF2EB] rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('events.create') }}" class="px-6 py-3 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition shadow">
                        + Créer un événement
                    </a>
                    <a href="{{ route('events.index') }}" class="px-6 py-3 bg-white text-[#D4A574] border-2 border-[#D4A574] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        Mes événements
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
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes événements récents</h3>
                @if(auth()->user()->hostedEvents()->latest()->take(5)->get()->isNotEmpty())
                    <div class="space-y-4">
                        @foreach(auth()->user()->hostedEvents()->latest()->take(5)->get() as $event)
                            <div class="flex items-center justify-between p-4 bg-[#FFF2EB] rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600">Status: {{ ucfirst($event->status) }} • {{ $event->participants()->count() }} participants</p>
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg text-sm hover:bg-[#c49560] transition">
                                    Gérer
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun événement créé.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>