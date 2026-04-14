{{-- @deprecated: Use Livewire or React for v2.0 --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Volunteering Missions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <span class="text-sm font-bold text-green-600">
                                    {{ $event->effectivePoints() }} pts
                                </span>
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
                        <p class="text-gray-500">No events available yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>