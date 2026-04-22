<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-heading font-bold text-[var(--neon-cyan)] uppercase tracking-wider drop-shadow-[0_0_10px_rgba(0,255,255,0.8)]">
            PROFILE INFORMATION
        </h2>
        <p class="mt-2 text-sm font-mono text-[var(--chrome-text)]/70">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div class="mb-5">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mb-5">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-[rgba(255,153,0,0.1)] border border-[var(--neon-orange)] rounded">
                    <p class="text-sm font-mono text-[var(--neon-orange)]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-[var(--neon-cyan)] hover:text-[var(--neon-magenta)] font-bold uppercase tracking-wider transition-colors">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-mono text-sm text-[var(--neon-cyan)] text-glow-cyan">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Interests (Categories) --}}
        <div class="mb-5">
            <x-input-label for="interests" :value="__('Centres d\'intérêt')" />
            <div id="interests" class="mt-3 flex flex-wrap gap-2">
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
                    <label class="inline-flex items-center gap-2 px-4 py-2 border-2 border-[var(--border-default)] bg-[rgba(26,16,60,0.4)] cursor-pointer transition-all duration-200 hover:border-[var(--neon-cyan)] {{ in_array($category, $userInterests) ? 'border-[var(--neon-cyan)] bg-[rgba(0,255,255,0.1)]' : '' }}">
                        <input type="checkbox" name="interests[]" value="{{ $category }}" 
                            {{ in_array($category, $userInterests) ? 'checked' : '' }}
                            class="rounded border-[var(--border-default)] bg-transparent text-[var(--neon-cyan)] focus:ring-[var(--neon-cyan)]">
                        <span class="text-sm font-mono text-[var(--chrome-text)]">{{ $category }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('interests')" class="mt-2" />
            <p class="text-xs text-[var(--chrome-text)]/60 mt-2 font-mono">SELECT ONE OR MORE CATEGORIES</p>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-[var(--neon-cyan)]/30">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-mono text-[var(--neon-cyan)]"
                >{{ __('Saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
