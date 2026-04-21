<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($notifications->count() > 0)
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">{{ $notifications->count() }} notifications</span>
                        <button onclick="markAllRead()" class="text-sm text-[#D4A574] hover:underline">
                            Tout marquer comme lu
                        </button>
                    </div>
                @endif

                <div class="divide-y divide-gray-100">
                    @forelse($notifications as $notification)
                        <div class="p-4 hover:bg-gray-50 transition {{ $notification->read ? 'bg-white' : 'bg-blue-50' }}">
                            <div class="flex items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">🔔</span>
                                        <p class="font-semibold text-gray-800">{{ $notification->title }}</p>
                                        @if(!$notification->read)
                                            <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Nouveau</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex flex-col gap-2">
                                    @if($notification->link)
                                        <a href="{{ $notification->link }}" class="text-[#D4A574] hover:underline text-sm">
                                            Voir
                                        </a>
                                    @endif
                                    @if(!$notification->read)
                                        <button onclick="markRead({{ $notification->id }})" class="text-gray-400 hover:text-gray-600 text-xs">
                                            Marquer lu
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p class="text-4xl mb-4">🔔</p>
                            <p>Aucune notification</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function markRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }

        function markAllRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    </script>
</x-app-layout>