<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFDCDC]">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-[#D4A574]">{{ \App\Models\User::where('role', 'student')->count() }}</p>
                        <p class="text-sm text-gray-500 mt-1">Étudiants</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFE8CD]">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-[#D4A574]">{{ \App\Models\User::where('role', 'partner')->count() }}</p>
                        <p class="text-sm text-gray-500 mt-1">Partenaires</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFD6BA]">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-[#D4A574]">{{ \App\Models\Event::where('status', 'pending')->count() }}</p>
                        <p class="text-sm text-gray-500 mt-1">Événements en attente</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFDCDC]">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-[#D4A574]">{{ \App\Models\Partner::where('kyc_status', 'pending')->count() }}</p>
                        <p class="text-sm text-gray-500 mt-1">KYC en attente</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-[#FFF2EB] rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.kyc.index') }}" class="px-6 py-3 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition shadow">
                        Vérifications KYC
                    </a>
                    <a href="{{ route('events.index') }}" class="px-6 py-3 bg-white text-[#D4A574] border-2 border-[#D4A574] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        Tous les événements
                    </a>
                </div>
            </div>

            {{-- Pending Events --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Événements en attente d'approbation</h3>
                @if(\App\Models\Event::where('status', 'pending')->count() > 0)
                    <p class="text-gray-500">Il y a des événements en attente.</p>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun événement en attente.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>