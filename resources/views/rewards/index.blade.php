<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rewards Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- User points balance --}}
            <div class="bg-indigo-600 rounded-lg p-4 mb-6 text-white flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Your Points Balance</p>
                    <p class="text-3xl font-bold">{{ auth()->user()->points_balance }} pts</p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-80">Current Grade</p>
                    <p class="text-xl font-semibold capitalize">{{ auth()->user()->grade }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($rewards as $reward)
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden flex flex-col">
                        @if($reward->image)
                            <img src="{{ asset('storage/' . $reward->image) }}" class="w-full h-40 object-cover" alt="{{ $reward->title }}">
                        @else
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                <span class="text-4xl">🎁</span>
                            </div>
                        @endif

                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                @if($reward->is_premium)
                                    <span class="text-xs font-medium px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Premium</span>
                                @endif
                                @if($reward->stock !== null && $reward->stock <= 5)
                                    <span class="text-xs font-medium px-2 py-1 bg-red-100 text-red-800 rounded">Low Stock</span>
                                @endif
                            </div>

                            <h3 class="font-semibold text-gray-900 mb-1">{{ $reward->title }}</h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $reward->description }}</p>

                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-lg font-bold text-green-600">{{ $reward->points_cost }} pts</span>
                                    @if($reward->stock !== null)
                                        <span class="text-xs text-gray-500">{{ $reward->stock }} left</span>
                                    @endif
                                </div>

                                @if(auth()->user()->can('reward.redeem'))
                                    @if($reward->isAccessibleBy(auth()->user()))
                                        @if(auth()->user()->points_balance >= $reward->points_cost)
                                            <form action="{{ route('rewards.redeem', $reward) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                                    Redeem
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-md text-sm cursor-not-allowed">
                                                Not enough points
                                            </button>
                                        @endif
                                    @else
                                        <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-md text-sm cursor-not-allowed">
                                            Grade required: {{ $reward->min_grade }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No rewards available yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>