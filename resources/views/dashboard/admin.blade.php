<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-6">
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\User::where('role', 'student')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Students</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ \App\Models\User::where('role', 'partner')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Partners</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ \App\Models\Event::where('status', 'pending')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Pending Events</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-red-600">{{ \App\Models\Partner::where('kyc_status', 'pending')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">KYC Pending</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white shadow-sm rounded-lg p-6 flex gap-4">
                <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">KYC Queue</a>
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">All Events</a>
            </div>

        </div>
    </div>
</x-app-layout>
