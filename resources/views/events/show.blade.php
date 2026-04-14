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
                            <span class="text-2xl font-bold text-green-600">{{ $event->effectivePoints() }} pts</span>
                        </div>

                        <p class="text-gray-700 mb-4">{{ $event->description }}</p>

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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>