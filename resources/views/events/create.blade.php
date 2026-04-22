<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-heading font-black text-[#00FFFF] text-glow-cyan uppercase tracking-wider">
            > CREATE MISSION
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-cyan)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-magenta)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-magenta)]"></div>

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> MISSION_TITLE</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--neon-cyan)] font-mono placeholder-[var(--neon-magenta)]/50 focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300"
                               placeholder="Enter mission title..." required>
                        @error('title')
                            <p class="text-sm text-[var(--neon-orange)] mt-2 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> MISSION_BRIEF</label>
                        <textarea id="description" name="description" rows="6" 
                                  class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-cyan)] transition-all duration-300 resize-none"
                                  placeholder="Describe the mission objective..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-[var(--neon-orange)] mt-2 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category & City --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> CATEGORY</label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                                   placeholder="e.g., Environment, Education">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> LOCATION_CITY</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                                   placeholder="City name..." required>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="address" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> LOCATION_ADDRESS</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono placeholder-[var(--neon-cyan)]/50 focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                               placeholder="Full street address..." required>
                    </div>

                    {{-- Date & Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> START_TIMESTAMP</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="ends_at" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> END_TIMESTAMP</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    {{-- Numbers --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="volunteer_quota" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> VOLUNTEER_CAPACITY</label>
                            <input type="number" name="volunteer_quota" id="volunteer_quota" min="1" value="{{ old('volunteer_quota') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="duration_hours" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> DURATION_HOURS</label>
                            <input type="number" name="duration_hours" id="duration_hours" min="1" value="{{ old('duration_hours') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label for="points_reward" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> POINTS_REWARD</label>
                            <input type="number" name="points_reward" id="points_reward" min="1" value="{{ old('points_reward') }}"
                                   class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-orange)] text-[var(--neon-orange)] font-mono focus:outline-none focus:shadow-[var(--glow-orange)] transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    {{-- Urgency Multiplier --}}
                    <div>
                        <label for="urgency_multiplier" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> URGENCY_MULTIPLIER (1-3)</label>
                        <input type="number" step="0.1" min="1" max="3" name="urgency_multiplier" id="urgency_multiplier" value="{{ old('urgency_multiplier', 1) }}"
                               class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-cyan)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300">
                        <p class="text-xs font-mono text-[var(--chrome-text)]/60 mt-2">RANGE: 1.0 (NORMAL) → 3.0 (CRITICAL)</p>
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label for="image" class="block text-sm font-mono uppercase tracking-widest text-[var(--neon-cyan)] mb-2">> MISSION_IMAGE</label>
                        <div class="border-2 border-dashed border-[var(--neon-cyan)] p-6 text-center hover:border-[var(--neon-magenta)] transition-colors duration-300 cursor-pointer relative overflow-hidden group">
                            <input type="file" name="image" id="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            <div class="space-y-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform duration-300">📷</span>
                                <p class="text-sm font-mono text-[var(--chrome-text)]">DROP IMAGE OR CLICK TO UPLOAD</p>
                                <p class="text-xs text-[var(--chrome-text)]/60">MAX 2MB • JPG/PNG/WEBP</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-sm text-[var(--neon-orange)] mt-2 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-4 pt-6 border-t-2 border-[var(--neon-cyan)]/30">
                        <button type="submit" class="btn-skew flex-1 px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-lg uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                            <span>⚡ DEPLOY MISSION</span>
                        </button>
                        <a href="{{ route('events.index') }}" class="btn-skew px-8 py-4 border-2 border-[var(--neon-magenta)] bg-transparent text-[var(--neon-magenta)] font-bold text-lg uppercase tracking-widest hover:bg-[var(--neon-magenta)] hover:text-black transition-all duration-200">
                            <span>✕ CANCEL</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>