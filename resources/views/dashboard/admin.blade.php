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
                @php
                    $pendingEvents = \App\Models\Event::where('status', 'pending')->with('partner')->latest()->get();
                @endphp
                
                @if($pendingEvents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">Titre</th>
                                    <th class="px-4 py-3 text-left">Partenaire</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Ville</th>
                                    <th class="px-4 py-3 text-left">Points</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pendingEvents as $event)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('events.show', $event) }}" class="text-[#D4A574] hover:underline font-medium">
                                                {{ $event->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $event->partner->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $event->starts_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $event->city }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $event->points_reward }} pts</td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-center gap-2">
                                                <form action="{{ route('events.approve', $event) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">
                                                        Approuver
                                                    </button>
                                                </form>
                                                <form action="{{ route('events.reject', $event) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">
                                                        Rejeter
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
                    <p class="text-gray-500 text-center py-4">Aucun événement en attente.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>