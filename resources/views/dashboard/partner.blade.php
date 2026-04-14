<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Partner Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- KYC Status --}}
            @if(! auth()->user()->kyc_verified)
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg p-4 text-sm">
                    ⚠️ Your account is pending KYC verification. You cannot publish events until approved by an admin.
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ auth()->user()->hostedEvents()->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total Events</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ auth()->user()->hostedEvents()->where('status', 'approved')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Active Events</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white shadow-sm rounded-lg p-6 flex gap-4">
                <a href="{{ route('events.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Create Event</a>
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm">My Events</a>
            </div>

        </div>
    </div>
</x-app-layout>
