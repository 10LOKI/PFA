<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-heading font-bold text-red-500 uppercase tracking-wider drop-shadow-[0_0_10px_#ef4444]">
            DELETE ACCOUNT
        </h2>
        <p class="mt-2 text-sm font-mono text-[var(--chrome-text)]/70">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <span>{{ __('Delete Account') }}</span>
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="glass-panel border-2 border-red-500 p-8 relative">
            <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-red-400"></div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-red-400"></div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <header class="!border-none !p-0">
                    <h2 class="text-2xl font-heading font-bold text-red-500 uppercase tracking-wider drop-shadow-[0_0_10px_#ef4444]">
                        Confirm Account Deletion
                    </h2>

                    <p class="mt-2 text-sm font-mono text-[var(--chrome-text)]/70">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                </header>

                <div class="mb-5">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full"
                        placeholder="{{ __('Enter your password') }}"
                        required
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-red-500/30">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        <span>{{ __('Cancel') }}</span>
                    </x-secondary-button>

                    <x-danger-button>
                        <span>{{ __('Delete Account') }}</span>
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
