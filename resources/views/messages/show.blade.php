<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversation avec ') }} {{ $conversation->participants->where('id', '!=', auth()->id())->first()->name ?? 'Unknown' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Messages --}}
                <div class="p-6 space-y-4 max-h-[500px] overflow-y-auto">
                    @forelse($conversation->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] rounded-xl px-4 py-2 {{ $message->sender_id === auth()->id() ? 'bg-[#D4A574] text-white' : 'bg-gray-100 text-gray-800' }}">
                                <p>{{ $message->body }}</p>
                                <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-white/70' : 'text-gray-500' }} mt-1">
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Aucun message</p>
                    @endforelse
                </div>

                {{-- Send message form --}}
                <div class="p-6 border-t border-gray-100 bg-[#FFF2EB]">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $conversation->participants->where('id', '!=', auth()->id())->first()->id }}">
                        <input type="text" name="body" placeholder="Écrivez votre message..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4A574] focus:border-transparent"
                               required>
                        <button type="submit" class="px-6 py-2 bg-[#D4A574] text-white rounded-lg font-medium hover:bg-[#c49560] transition">
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('messages.index') }}" class="text-[#D4A574] hover:underline">← Retour aux conversations</a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
        <script>
            const pusher = new Pusher('{{ config('services.pusher.key') }}', {
                cluster: '{{ config('services.pusher.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('conversation.{{ $conversation->id }}');
            channel.bind('Illuminate\\Broadcasting\\MessageSent', function(data) {
                if (data.message.sender_id !== {{ auth()->id() }}) {
                    location.reload();
                }
            });
        </script>
    @endpush
</x-app-layout>