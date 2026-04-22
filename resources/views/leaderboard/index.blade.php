<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                {{-- Stats --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-900">{{ __('Top Volunteers') }}</h3>
                        <div class="flex space-x-3">
                            @if(isset($city))
                                <a href="{{ route('leaderboard.index') }}" class="px-3 py-1 text-sm bg-gray-100 rounded-md hover:bg-gray-200">
                                    {{ __('National') }}
                                </a>
                            @else
                                <span class="px-3 py-1 text-sm bg-indigo-100 text-indigo-800 rounded-md">
                                    {{ __('National') }}
                                </span>
                            @endif
                            
                            <a href="{{ route('leaderboard.weekly') }}" class="px-3 py-1 text-sm bg-gray-100 rounded-md hover:bg-gray-200 {{ request()->routeIs('leaderboard.weekly') ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                {{ __('This Week') }}
                            </a>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        Rankings are based on total points balance. Updated in real-time.
                    </p>
                </div>
                
                {{-- Leaderboard Table --}}
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rank
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('User') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Grade') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Points') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Hours') }}
                                    </th>
                                    @if(!isset($city) && !isset($establishmentId))
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('City') }}
                                        </th>
                                    @endif
                                    @if(!isset($establishmentId))
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Establishment') }}
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @if($users->isEmpty())
                                    <tr>
                                        <td class="px-6 py-4 text-center text-gray-500" colspan="6">
                                            {{ __('No data available yet. Start participating in events to climb the ranks!') }}
                                        </td>
                                    </tr>
                                @else
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-sm font-medium text-indigo-800">
                                                        {{ $user->rank }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $user->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            @if($user->establishment)
                                                                {{ $user->establishment->name }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                             class="h-10 w-10 rounded-full mr-3" 
                                                             alt="{{ $user->name }}">
                                                    @else
                                                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-bold text-gray-600 uppercase">
                                                                {{ strtoupper($user->name[0] ?? '?') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs capitalize">
                                                    {{ $user->grade }}
                                                </span>
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{ number_format($user->points_balance) }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{ number_format($user->total_hours) }}h
                                            </td>
                                            
                                            @if(!isset($city) && !isset($establishmentId))
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                                    {{ $user->city ?? 'N/A' }}
                                                </td>
                                            @endif
                                            
                                            @if(!isset($establishmentId))
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                                    @if($user->establishment)
                                                        {{ $user->establishment->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination --}}
                @if($users->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>