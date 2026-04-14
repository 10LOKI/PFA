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
                </div>
            </div>

            {{-- Recent Events --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes événements récents</h3>
                <p class="text-gray-500 text-center py-8">Aucun événement créé.</p>
            </div>

        </div>
    </div>
</x-app-layout>