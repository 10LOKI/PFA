<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reward') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form action="{{ route('rewards.update', $reward) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Title') }}
                        </label>
                        <input type="text" name="title" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('title', $reward->title) }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $reward->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Image') }}
                        </label>
                        @if($reward->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $reward->image) }}" class="w-24 h-24 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Points Cost') }}
                            </label>
                            <input type="number" name="points_cost" required min="1"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('points_cost', $reward->points_cost) }}">
                            @error('points_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Stock (leave empty for unlimited)') }}
                            </label>
                            <input type="number" name="stock" min="0"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('stock', $reward->stock) }}">
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Minimum Grade Required') }}
                        </label>
                        <select name="min_grade" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="novice" {{ old('min_grade', $reward->min_grade) == 'novice' ? 'selected' : '' }}>Novice</option>
                            <option value="pilier" {{ old('min_grade', $reward->min_grade) == 'pilier' ? 'selected' : '' }}>Pilier</option>
                            <option value="ambassadeur" {{ old('min_grade', $reward->min_grade) == 'ambassadeur' ? 'selected' : '' }}>Ambassadeur</option>
                        </select>
                        @error('min_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Expiration Date') }}
                        </label>
                        <input type="date" name="expires_at"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('expires_at', $reward->expires_at?->format('Y-m-d')) }}">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-6 mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_premium" id="is_premium" value="1"
                                {{ old('is_premium', $reward->is_premium) ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="is_premium" class="ml-2 text-sm text-gray-700">
                                {{ __('Premium Reward') }}
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $reward->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">
                                {{ __('Active') }}
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <form action="{{ route('rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                {{ __('Delete') }}
                            </button>
                        </form>

                        <div class="flex gap-4">
                            <a href="{{ route('dashboard.partner') }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                {{ __('Update Reward') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>