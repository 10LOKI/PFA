<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]">
            <span class="text-glow-cyan">EDIT MISSION</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> MISSION_TITLE</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--neon-cyan)] font-mono placeholder-[var(--neon-magenta)]/50 focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                               placeholder="Mission title..." required>
                        @error('title')
                            <p class="text-sm text-[var(--neon-orange)] mt-2 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> MISSION_BRIEF</label>
                        <textarea id="description" name="description" rows="6" 
                                  class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300 resize-none"
                                  placeholder="Describe the mission..." required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-[var(--neon-orange)] mt-2 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category & City --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> CATEGORY</label>
                            <select name="category" id="category" class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300">
                                <option value="">Select a category...</option>
                                <option value="Environment" {{ $event->category === 'Environment' ? 'selected' : '' }}>Environment</option>
                                <option value="Education" {{ $event->category === 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Health" {{ $event->category === 'Health' ? 'selected' : '' }}>Health</option>
                                <option value="Technology" {{ $event->category === 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Community" {{ $event->category === 'Community' ? 'selected' : '' }}>Community</option>
                            </select>
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> LOCATION_CITY</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $event->city) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="address" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> LOCATION_ADDRESS</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $event->address) }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300"
                               required>
                    </div>

                    {{-- Date & Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> START_TIMESTAMP</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="ends_at" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> END_TIMESTAMP</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $event->ends_at->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    {{-- Numbers --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="volunteer_quota" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> VOLUNTEER_CAPACITY</label>
                            <input type="number" name="volunteer_quota" id="volunteer_quota" min="1" value="{{ old('volunteer_quota', $event->volunteer_quota) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="duration_hours" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> DURATION_HOURS</label>
                            <input type="number" name="duration_hours" id="duration_hours" min="1" value="{{ old('duration_hours', $event->duration_hours) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="points_reward" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> POINTS_REWARD</label>
                            <input type="number" name="points_reward" id="points_reward" min="1" value="{{ old('points_reward', $event->points_reward) }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    {{-- Urgency Multiplier --}}
                    <div>
                        <label for="urgency_multiplier" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-magenta)] mb-2">> URGENCY_MULTIPLIER</label>
                        <input type="number" step="0.1" min="1" max="3" name="urgency_multiplier" id="urgency_multiplier" value="{{ old('urgency_multiplier', $event->urgency_multiplier) }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300">
                        <p class="text-xs font-mono text-[var(--chrome-text)]/60 mt-2">VALID RANGE: 1.0 (NORMAL) → 3.0 (CRITICAL)</p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-4 pt-6 border-t-2 border-[var(--neon-magenta)]/30">
                        <a href="{{ route('events.show', $event) }}" class="btn-skew px-8 py-4 border-2 border-[var(--chrome-text)]/30 text-[var(--chrome-text)] font-bold text-sm uppercase tracking-widest hover:border-[var(--neon-magenta)] hover:text-[var(--neon-magenta)] transition-all duration-200">
                            <span>✕ CANCEL</span>
                        </a>
                        <button type="submit" class="btn-skew flex-1 px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-lg uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                            <span>⚡ UPDATE MISSION</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>