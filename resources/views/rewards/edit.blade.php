<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl md:text-3xl font-heading font-black text-[var(--neon-magenta)] uppercase tracking-wider drop-shadow-[0_0_15px_#FF00FF]">
            <span class="text-glow-magenta">EDIT REWARD</span>
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel border-2 border-[var(--neon-magenta)] p-8 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-[var(--neon-cyan)]"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-[var(--neon-cyan)]"></div>

                <form action="{{ route('rewards.update', $reward) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="block w-full" :value="old('title', $reward->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono focus:outline-none focus:shadow-[var(--glow-magenta)] transition-all duration-300 resize-none">{{ old('description', $reward->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        @if($reward->image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $reward->image) }}" class="w-24 h-24 object-cover rounded border-2 border-[var(--neon-magenta)]" alt="Current image">
                            </div>
                        @endif
                        <div class="border-2 border-dashed border-[var(--neon-magenta)] p-6 text-center hover:border-[var(--neon-cyan)] transition-colors duration-300 cursor-pointer relative overflow-hidden group mt-2">
                            <input type="file" name="image" id="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            <div class="space-y-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform duration-300">📷</span>
                                <p class="text-sm font-mono text-[var(--chrome-text)]">DROP IMAGE OR CLICK TO UPLOAD</p>
                                <p class="text-xs text-[var(--chrome-text)]/60">MAX 2MB • JPG/PNG/WEBP</p>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    {{-- Numbers --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="points_cost" :value="__('Points Cost')" />
                            <x-text-input id="points_cost" name="points_cost" type="number" min="1" class="block w-full" :value="old('points_cost', $reward->points_cost)" required />
                            <x-input-error :messages="$errors->get('points_cost')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock" :value="__('Stock (leave empty for unlimited)')" />
                            <x-text-input id="stock" name="stock" type="number" min="0" class="block w-full" :value="old('stock', $reward->stock)" />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Minimum Grade --}}
                    <div>
                        <x-input-label for="min_grade" :value="__('Minimum Grade Required')" />
                        <select id="min_grade" name="min_grade" required class="block w-full px-4 py-3 bg-[var(--void-bg)] border-2 border-[var(--neon-magenta)] text-[var(--chrome-text)] font-mono focus:outline-none focus:border-[var(--neon-cyan)] focus:ring-2 focus:ring-[var(--neon-cyan)] focus:ring-opacity-30 transition-all duration-200">
                            <option value="novice" {{ old('min_grade', $reward->min_grade) == 'novice' ? 'selected' : '' }}>NOVICE</option>
                            <option value="pilier" {{ old('min_grade', $reward->min_grade) == 'pilier' ? 'selected' : '' }}>PILLAR</option>
                            <option value="ambassadeur" {{ old('min_grade', $reward->min_grade) == 'ambassadeur' ? 'selected' : '' }}>AMBASSADOR</option>
                        </select>
                        <x-input-error :messages="$errors->get('min_grade')" class="mt-2" />
                    </div>

                    {{-- Expiration Date --}}
                    <div>
                        <x-input-label for="expires_at" :value="__('Expiration Date')" />
                        <x-text-input id="expires_at" name="expires_at" type="date" class="block w-full" :value="old('expires_at', $reward->expires_at?->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                    </div>

                    {{-- Flags --}}
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_premium" id="is_premium" value="1"
                                {{ old('is_premium', $reward->is_premium) ? 'checked' : '' }}
                                class="rounded border-[var(--border-default)] bg-transparent text-[var(--neon-orange)] focus:ring-[var(--neon-orange)]">
                            <span class="text-sm font-mono text-[var(--chrome-text)]">PREMIUM REWARD</span>
                        </label>

                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $reward->is_active) ? 'checked' : '' }}
                                class="rounded border-[var(--border-default)] bg-transparent text-[var(--neon-cyan)] focus:ring-[var(--neon-cyan)]">
                            <span class="text-sm font-mono text-[var(--chrome-text)]">ACTIVE</span>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-[var(--neon-magenta)]/30">
                        <form action="{{ route('rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full btn-skew px-8 py-4 border-2 border-red-500 text-red-500 font-bold text-sm uppercase tracking-widest hover:bg-red-500 hover:text-black transition-all duration-200">
                                <span>🗑 DELETE</span>
                            </button>
                        </form>

                        <div class="flex gap-4 flex-1">
                            <a href="{{ route('dashboard.partner') }}" class="btn-skew px-8 py-4 border-2 border-[var(--chrome-text)]/30 text-[var(--chrome-text)] font-bold text-sm uppercase tracking-widest hover:border-[var(--neon-magenta)] hover:text-[var(--neon-magenta)] transition-all duration-200">
                                <span>✕ CANCEL</span>
                            </a>
                            <button type="submit" class="btn-skew flex-1 px-8 py-4 border-2 border-[var(--neon-cyan)] bg-[var(--neon-cyan)] text-black font-bold text-lg uppercase tracking-widest shadow-[var(--glow-cyan)] hover:shadow-[0_0_30px_#00FFFF] transition-all duration-200">
                                <span>⚡ UPDATE REWARD</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
