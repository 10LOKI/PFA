<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <form action="{{ route('users.index') }}" method="get" class="flex gap-4">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $search ?? '' }}"
                            placeholder="Search by name or email..." 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4A574] focus:border-transparent"
                        >
                        <button type="submit" class="px-6 py-2 bg-[#D4A574] text-white rounded-lg hover:bg-[#c49463] transition">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($users as $user)
                    <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#FFDCDC] rounded-full flex items-center justify-center">
                            <span class="text-xl font-bold text-[#D4A574]">
                                {{ strtoupper($user->name[0] ?? '?') }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            @if($user->role)
                                <span class="inline-block mt-1 text-xs bg-[#FFF2EB] text-[#D4A574] px-2 py-1 rounded">
                                    {{ $user->role }}
                                </span>
                            @endif
                        </div>
                        <form action="{{ route('messages.start') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg hover:bg-[#c49463] transition text-sm">
                                Message
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <p>No users found</p>
                    </div>
                @endforelse
            </div>

            @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>