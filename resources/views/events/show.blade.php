{{-- @deprecated: Use Livewire or React for v2.0 --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image --}}
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-64 object-cover rounded-lg" alt="{{ $event->title }}">
                    @endif

                    {{-- Info --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                {{ $event->category ?? 'General' }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if($event->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">En attente</span>
                                @elseif($event->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Approuvé</span>
                                @elseif($event->status === 'rejected')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Rejeté</span>
                                @elseif($event->status === 'cancelled')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Annulé</span>
                                @endif
                                <span class="text-2xl font-bold text-green-600">{{ $event->effectivePoints() }} pts</span>
                            </div>
                        </div>

                         <p class="text-gray-700 mb-4">{{ $event->description }}</p>

                         {{-- Like button --}}
                         @if(auth()->user()->can('event.register'))
                             <div class="mb-4">
                                 @if($event->isLikedBy(auth()->user()))
                                     <form action="{{ route('events.unlike', $event) }}" method="POST" class="inline">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-600 transition">
                                             <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                                 <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                             </svg>
                                             <span class="font-semibold">{{ $event->likesCount }} {{ $event->likesCount === 1 ? 'Like' : 'Likes' }}</span>
                                         </button>
                                     </form>
                                 @else
                                     <form action="{{ route('events.like', $event) }}" method="POST" class="inline">
                                         @csrf
                                         <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition">
                                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                             </svg>
                                             <span class="font-semibold">{{ $event->likesCount }} {{ $event->likesCount === 1 ? 'Like' : 'Likes' }}</span>
                                         </button>
                                     </form>
                                 @endif
                             </div>
                         @endif

                         <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">📍 Location:</span> {{ $event->city }}, {{ $event->address }}
                            </div>
                            <div>
                                <span class="font-medium">📅 Date:</span> {{ $event->starts_at->format('d M Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">⏱ Duration:</span> {{ $event->duration_hours }}h
                            </div>
                            <div>
                                <span class="font-medium">👥 Volunteers:</span> {{ $event->participants()->count() }}/{{ $event->volunteer_quota }}
                            </div>
                        </div>
                    </div>

                    {{-- Comments section placeholder --}}
                    @if($event->comments->count() > 0)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="font-semibold mb-4">Comments</h3>
                            @foreach($event->comments as $comment)
                                <div class="border-b py-3">
                                    <p class="text-sm">{{ $comment->body }}</p>
                                    <span class="text-xs text-gray-500">{{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    {{-- Partner QR (only for event owner) --}}
                    @if(auth()->user()->can('event.generate-qr') && auth()->id() === $event->partner_id)
                        <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                            <h3 class="font-semibold mb-4">Check-in QR Code</h3>
                            <img src="{{ route('events.qr', $event) }}" alt="QR Code" class="mx-auto mb-4">
                            <p class="text-xs text-gray-500 mb-4">Scan to validate student presence</p>

                            {{-- Check-in form --}}
                            <form action="{{ route('events.checkin', $event) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="text" name="qr_token" placeholder="Enter QR token from scanner" 
                                       class="w-full px-3 py-2 border rounded-md text-sm" required>
                                <select name="student_id" class="w-full px-3 py-2 border rounded-md text-sm" required>
                                    <option value="">Select Student</option>
                                    @foreach($event->participants()->wherePivot('status', 'registered')->get() as $participant)
                                        <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                    Confirm Check-in
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Registration / Status --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        @php
                            $isRegistered = $event->participants()->where('user_id', auth()->id())->exists();
                            $pivot = $isRegistered ? $event->participants()->where('user_id', auth()->id())->first()->pivot : null;
                        @endphp

                        @if($isRegistered)
                            <div class="text-center">
                                @if($pivot->checked_in_at)
                                    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4">
                                        ✅ Checked in — {{ $pivot->points_earned }} pts earned
                                    </div>
                                @else
                                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-4">
                                        📋 Registered — Show up and get scanned!
                                    </div>
                                @endif
                                <form action="{{ route('events.unregister', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm hover:underline">Cancel registration</button>
                                </form>
                            </div>
                        @elseif(!$event->isFull() && auth()->user()->can('event.register'))
                            <form action="{{ route('events.register', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Register for Event
                                </button>
                            </form>
                        @elseif($event->isFull())
                            <div class="text-center text-gray-500">Event is full</div>
                        @endif
                    </div>

                    {{-- Participants Preview with Modal --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold">
                                Participants ({{ $event->participants->count() }})
                            </h3>
                        </div>

                        {{-- First 5 avatars inline --}}
                        <div class="flex items-center -space-x-2 overflow-hidden py-2">
                            @foreach($event->participants->take(5) as $participant)
                                <div class="h-10 w-10 rounded-full bg-[#FFDCDC] ring-2 ring-white flex items-center justify-center text-xs font-bold text-[#D4A574]" title="{{ $participant->name }}">
                                    {{ strtoupper($participant->name[0]) }}
                                </div>
                            @endforeach
                            @if($event->participants->count() > 5)
                                <div class="h-10 w-10 rounded-full bg-gray-200 ring-2 ring-white flex items-center justify-center text-xs text-gray-600 cursor-pointer hover:bg-gray-300"
                                     x-data="{ open: false }"
                                     @click="open = true">
                                    +{{ $event->participants->count() - 5 }}
                                </div>
                            @endif
                        </div>

                        {{-- Full participants modal --}}
                        @if($event->participants->count() > 0)
                            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-lg font-semibold">Tous les participants</h4>
                                        <button @click="open = false" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($event->participants as $participant)
                                            <div class="flex items-center gap-3 p-2 rounded-lg bg-[#FFF2EB]">
                                                <div class="w-10 h-10 bg-[#FFDCDC] rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span class="text-sm font-bold text-[#D4A574]">
                                                        {{ strtoupper($participant->name[0]) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $participant->name }}</p>
                                                    <p class="text-xs text-gray-500 capitalize">{{ $participant->grade }}</p>
                                                </div>
                                                @if($participant->id !== auth()->id())
                                                    <form action="{{ route('messages.start') }}" method="post" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $participant->id }}">
                                                        <button type="submit" class="text-xs px-2 py-1 bg-[#D4A574] text-white rounded hover:bg-[#c49463]">
                                                            Message
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    {{-- Likes Social Proof --}}
                    @if($event->likesCount > 0)
                        <div class="bg-white shadow-sm rounded-lg p-6 mt-6">
                            <h3 class="font-semibold mb-3">
                                ❤️ {{ $event->likesCount }} {{ $event->likesCount === 1 ? 'person' : 'personnes' }} aiment cet événement
                            </h3>

                            <div class="flex items-center -space-x-2 overflow-hidden">
                                @foreach($event->likedBy->take(5) as $liker)
                                    <div class="h-10 w-10 rounded-full bg-red-100 ring-2 ring-white flex items-center justify-center text-xs font-bold text-red-600" title="{{ $liker->name }}">
                                        {{ strtoupper($liker->name[0]) }}
                                    </div>
                                @endforeach
                                @if($event->likesCount > 5)
                                    <div class="h-10 w-10 rounded-full bg-gray-100 ring-2 ring-white flex items-center justify-center text-xs text-gray-600">
                                        +{{ $event->likesCount - 5 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>