<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Conversations</h3>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-[#D4A574] text-white rounded-lg hover:bg-[#c49463] transition text-sm">
                        New Message
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($conversations as $conversation)
                        @php
                            $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                            $lastMessage = $conversation->messages->first();
                            $unread = $conversation->messages()
                                ->where('sender_id', '!=', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                        @endphp

                        <a href="{{ route('messages.show', $conversation) }}" class="block p-6 hover:bg-[#FFF2EB] transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-[#FFDCDC] rounded-full flex items-center justify-center">
                                    <span class="text-lg font-bold text-[#D4A574]">
                                        {{ strtoupper($other->name[0] ?? '?') }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">{{ $other->name }}</p>
                                        @if($lastMessage)
                                            <span class="text-xs text-gray-500">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $lastMessage->body ?? 'Pas de messages' }}
                                    </p>
                                </div>
                                @if($unread > 0)
                                    <span class="bg-[#D4A574] text-white text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>Aucune conversation</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>