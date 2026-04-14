<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ auth()->user()->points_balance }}</p>
                    <p class="text-sm text-gray-500 mt-1">Points Balance</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ auth()->user()->total_hours }}h</p>
                    <p class="text-sm text-gray-500 mt-1">Certified Hours</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-yellow-600 capitalize">{{ auth()->user()->grade }}</p>
                    <p class="text-sm text-gray-500 mt-1">Current Grade</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white shadow-sm rounded-lg p-6 flex gap-4">
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Browse Events</a>
                <a href="{{ route('rewards.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">Marketplace</a>
            </div>

        </div>
    </div>
</x-app-layout>
