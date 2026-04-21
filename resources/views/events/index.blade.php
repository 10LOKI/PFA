{{-- @deprecated: Use Livewire or React for v2.0 --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Volunteering Missions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- Sidebar Filters --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 shadow-sm sticky top-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Filtrer</h3>
                        <form method="GET" action="{{ route('events.index') }}">
                            {{-- Search --}}
                            <div class="mb-4">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Mots-clés..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- City --}}
                            <div class="mb-4">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                <input type="text" name="city" id="city" value="{{ request('city') }}"
                                    placeholder="Nom de la ville..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Interests (Categories) --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Centres d'intérêt</label>
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    @foreach($categories as $category)
                                        @php
                                            // Determine current interests: either from request or from user profile
                                            $currentInterests = old('interests', auth()->user()->interests ?? []);
                                            $selected = is_array($currentInterests) && in_array($category, $currentInterests);
                                        @endphp
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="interests[]" value="{{ $category }}"
                                                {{ $selected ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $category }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Sélectionnez un ou plusieurs</p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    Appliquer
                                </button>
                                <a href="{{ route('events.index') }}" class="text-center text-sm text-gray-600 hover:underline">
                                    Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Events Grid --}}
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($events as $event)
                            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-48 object-cover" alt="{{ $event->title }}">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium px-2 py-1 bg-indigo-100 text-indigo-800 rounded">
                                            {{ $event->category ?? 'General' }}
                                        </span>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-bold text-green-600">
                                                {{ $event->effectivePoints() }} pts
                                            </span>
                                            @if(auth()->user()->can('event.register'))
                                                <form action="{{ route('events.like', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-400 hover:text-red-500 transition" title="{{ $event->isLikedBy(auth()->user()) ? 'Unlike' : 'Like' }}">
                                                        <svg class="w-5 h-5" fill="{{ $event->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-3">{{ $event->city }}</p>

                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <span>{{ $event->starts_at->format('d M Y') }}</span>
                                        <span>{{ $event->participants()->count() }}/{{ $event->volunteer_quota }} volunteers</span>
                                    </div>

                                    <a href="{{ route('events.show', $event) }}" class="block text-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500">No events match your filters.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
