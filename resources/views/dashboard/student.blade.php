<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bienvenue, ') }} {{ auth()->user()->name }} !
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFDCDC]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#FFE8CD] rounded-full flex items-center justify-center">
                            <span class="text-2xl">🎯</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#D4A574]">{{ auth()->user()->points_balance }}</p>
                            <p class="text-sm text-gray-500">Points Balance</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFE8CD]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#FFD6BA] rounded-full flex items-center justify-center">
                            <span class="text-2xl">⏱️</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#D4A574]">{{ auth()->user()->total_hours }}h</p>
                            <p class="text-sm text-gray-500">Heures Certifiées</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg border-t-4 border-[#FFD6BA]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#FFDCDC] rounded-full flex items-center justify-center">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-[#D4A574] capitalize">{{ auth()->user()->grade }}</p>
                            <p class="text-sm text-gray-500">Grade Actuel</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grade Progress --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Progression vers le prochain grade</h3>
                <div class="relative pt-1">
                    @php
                        $thresholds = ['novice' => 0, 'pilier' => 50, 'ambassadeur' => 150];
                        $currentGrade = auth()->user()->grade;
                        $currentHours = auth()->user()->total_hours;
                        $nextGrade = null;
                        $nextThreshold = null;
                        
                        foreach ($thresholds as $grade => $threshold) {
                            if ($threshold > $currentHours) {
                                $nextGrade = $grade;
                                $nextThreshold = $threshold;
                                break;
                            }
                        }
                    @endphp
                    
                    @if($nextGrade)
                        <div class="h-4 bg-[#FFE8CD] rounded-full overflow-hidden">
                            <div class="h-full bg-[#D4A574] rounded-full transition-all" style="width: {{ ($currentHours / $nextThreshold) * 100 }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">{{ $currentHours }}h / {{ $nextThreshold }}h pour devenir {{ $nextGrade }}</p>
                    @else
                        <div class="h-4 bg-[#D4A574] rounded-full"></div>
                        <p class="text-sm text-[#D4A574] font-semibold mt-2">🎉 Grade maximum atteint !</p>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-[#FFF2EB] rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('events.index') }}" class="px-6 py-3 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition shadow">
                        🚀 Trouver un événement
                    </a>
                    <a href="{{ route('rewards.index') }}" class="px-6 py-3 bg-white text-[#D4A574] border-2 border-[#D4A574] rounded-lg font-medium hover:bg-[#FFE8CD] transition">
                        🎁 Voir les rewards
                    </a>
                </div>
            </div>

            {{-- Recent Events (placeholder) --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes événements récents</h3>
                <p class="text-gray-500 text-center py-8">Aucun événement participation récente.</p>
                <div class="text-center">
                    <a href="{{ route('events.index') }}" class="text-[#D4A574] hover:underline">Découvrir des événements →</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>