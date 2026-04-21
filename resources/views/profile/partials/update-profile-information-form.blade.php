<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Interests (Categories) --}}
            <div>
                <x-input-label for="interests" :value="__('Centres d\'intérêt')" />
                <div id="interests" class="mt-2 flex flex-wrap gap-2">
                    @php
                        $categories = \App\Models\Event::select('category')
                            ->whereNotNull('category')
                            ->distinct()
                            ->orderBy('category')
                            ->pluck('category')
                            ->toArray();
                        $userInterests = old('interests', $user->interests ?? []);
                        if (!is_array($userInterests)) {
                            $userInterests = [$userInterests];
                        }
                    @endphp

                    @foreach($categories as $category)
                        <label class="inline-flex items-center gap-1 px-3 py-1 rounded-full border {{ in_array($category, $userInterests) ? 'bg-indigo-100 border-indigo-200 text-indigo-800' : 'bg-gray-50 border-gray-200 text-gray-600' }} cursor-pointer hover:bg-indigo-50 transition">
                            <input type="checkbox" name="interests[]" value="{{ $category }}" 
                                {{ in_array($category, $userInterests) ? 'checked' : '' }}
                                class="hidden">
                            <span class="text-sm">{{ $category }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('interests')" />
                <p class="text-xs text-gray-500 mt-1">Sélectionnez les catégories qui vous intéressent.</p>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
</section>
