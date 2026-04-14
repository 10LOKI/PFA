{{-- @deprecated: Use Livewire or React for v2.0 --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Volunteering Mission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category')" />
                        </div>
                        <div>
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="starts_at" :value="__('Start Date & Time')" />
                            <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at')" required />
                        </div>
                        <div>
                            <x-input-label for="ends_at" :value="__('End Date & Time')" />
                            <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at')" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="volunteer_quota" :value="__('Volunteer Quota')" />
                            <x-text-input id="volunteer_quota" name="volunteer_quota" type="number" min="1" class="mt-1 block w-full" :value="old('volunteer_quota')" required />
                        </div>
                        <div>
                            <x-input-label for="duration_hours" :value="__('Duration (hours)')" />
                            <x-text-input id="duration_hours" name="duration_hours" type="number" min="1" class="mt-1 block w-full" :value="old('duration_hours')" required />
                        </div>
                        <div>
                            <x-input-label for="points_reward" :value="__('Points Reward')" />
                            <x-text-input id="points_reward" name="points_reward" type="number" min="1" class="mt-1 block w-full" :value="old('points_reward')" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="urgency_multiplier" :value="__('Urgency Multiplier (1-3)')" />
                        <x-text-input id="urgency_multiplier" name="urgency_multiplier" type="number" step="0.1" min="1" max="3" class="mt-1 block w-full" :value="old('urgency_multiplier', 1)" />
                        <p class="text-xs text-gray-500 mt-1">1 = Normal, 2 = Urgent, 3 = Critical</p>
                    </div>

                    <div>
                        <x-input-label for="image" :value="__('Event Image')" />
                        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Create Mission') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>