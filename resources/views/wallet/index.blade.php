<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallet & Points History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Balance Cards --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900">{{ __('Your Points Balance') }}</h3>
                            <p class="text-4xl font-bold text-indigo-600">{{ number_format($user->points_balance) }} pts</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                <p class="text-sm text-gray-500">{{ __('Earned') }}</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($totalEarned) }} pts</p>
                            </div>
                            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                <p class="text-sm text-gray-500">{{ __('Spent') }}</p>
                                <p class="text-2xl font-bold text-red-600">{{ number_format($totalSpent) }} pts</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Transaction Filters --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Filters') }}</h3>
                        
                        <form method="GET" action="{{ route('wallet.index') }}">
                            <div class="space-y-3">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }}</label>
                                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">{{ __('All Types') }}</option>
                                        <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>
                                            {{ __('Earned from Events') }}
                                        </option>
                                        <option value="spent" {{ request('type') === 'spent' ? 'selected' : '' }}>
                                            {{ __('Spent on Rewards') }}
                                        </option>
                                        <option value="burned" {{ request('type') === 'burned' ? 'selected' : '' }}>
                                            {{ __('Points Burned') }}
                                        </option>
                                        <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>
                                            {{ __('Adjusted') }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        {{ __('Apply') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                {{-- Transaction History --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ __('Transaction History') }}</h3>
                            @if($transactions->count() > 0)
                                <span class="text-sm text-gray-500">{{ $transactions->total() }} transactions</span>
                            @endif
                        </div>
                        
                        @if($transactions->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-gray-500">{{ __('No transactions yet. Start participating in events to earn points!') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($transactions as $transaction)
                                    <div class="border-l-4 border-indigo-500 pl-4 py-3 bg-gray-50">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $transaction->description }}</p>
                                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                            <div class="text-right space-x-2">
                                                @if($transaction->amount > 0)
                                                    <span class="text-green-600 font-medium">+{{ number_format($transaction->amount) }} pts</span>
                                                    <span class="text-xs text-green-500">Balance: {{ number_format($transaction->balance_after) }} pts</span>
                                                @else
                                                    <span class="text-red-600 font-medium">{{ number_format($transaction->amount) }} pts</span>
                                                    <span class="text-xs text-red-500">Balance: {{ number_format($transaction->balance_after) }} pts</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- Source info --}}
                                        @if($transaction->source)
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ Str::of(class_basename($transaction->source))->replace('App\Models\\', '')->replace('\\', ' ') }}
                                                @if($transaction->source_type === 'event')
                                                    - {{ $transaction->source->title }}
                                                @elseif($transaction->source_type === 'reward_redemption')
                                                    - {{ $transaction->source->reward->title }}
                                                @endif
                                            </div>
                                        @elseif($transaction->source_type && $transaction->source_id)
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->source_type)) }} #{{ $transaction->source_id }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>