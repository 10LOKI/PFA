<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <style>[x-cloak] { display: none !important; }</style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-20 right-4 z-50 max-w-md">
                    <div class="glass-panel border-2 border-green-400 bg-[rgba(34,197,94,0.1)] p-4 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-green-400"></div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-green-400"></div>
                        <p class="text-sm font-mono text-green-400 flex items-center gap-2">
                            <span class="text-xl">✓</span>
                            <span>{{ session('success') }}</span>
                        </p>
                        <button @click="show = false" class="absolute top-2 right-2 text-green-400 hover:text-green-300 text-xl font-bold">&times;</button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-20 right-4 z-50 max-w-md">
                    <div class="glass-panel border-2 border-red-500 bg-[rgba(239,68,68,0.1)] p-4 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-2 border-r-2 border-red-500"></div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-2 border-l-2 border-red-500"></div>
                        <p class="text-sm font-mono text-red-500 flex items-center gap-2">
                            <span class="text-xl">✗</span>
                            <span>{{ session('error') }}</span>
                        </p>
                        <button @click="show = false" class="absolute top-2 right-2 text-red-500 hover:text-red-300 text-xl font-bold">&times;</button>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')

        <!-- Custom Delete Confirmation Modal -->
        <div id="delete-confirm-modal" x-data="{ open: false, title: '' }"
             x-show="open"
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
             style="display: none;">
            <div class="glass-panel border-2 border-red-500 p-8 max-w-md w-full mx-4 relative">
                <div class="absolute -top-2 -right-2 w-8 h-8 border-t-2 border-r-2 border-red-500"></div>
                <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-2 border-l-2 border-red-500"></div>

                <h3 class="text-2xl font-heading font-bold text-red-500 uppercase tracking-wider mb-4 drop-shadow-[0_0_10px_#EF4444]">
                    ⚠ CONFIRM DELETION
                </h3>

                <p class="text-sm font-mono text-[var(--chrome-text)] mb-6">
                    Mission <span class="text-[var(--neon-magenta)] font-bold" x-text="title"></span> will be permanently removed from the system.
                    <br><br>
                    <span class="text-[var(--neon-orange)]">This action cannot be undone.</span>
                </p>

                <div class="flex gap-4">
                    <button type="button"
                            @click="open = false"
                            class="btn-skew flex-1 px-6 py-3 border-2 border-[var(--chrome-text)]/30 text-[var(--chrome-text)] font-bold text-sm uppercase tracking-widest hover:border-[var(--neon-magenta)] hover:text-[var(--neon-magenta)] transition-all duration-200">
                        <span>✕ ABORT</span>
                    </button>
                    <button type="button"
                            @click="deleteConfirmed()"
                            class="btn-skew flex-1 px-6 py-3 border-2 border-red-500 bg-red-500 text-black font-bold text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(239,68,68,0.4)] hover:shadow-[0_0_30px_rgba(239,68,68,0.6)] transition-all duration-200">
                        <span>✓ CONFIRM DELETE</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function confirmDelete(form, title) {
                const modal = document.getElementById('delete-confirm-modal');
                const alpineComponent = modal.__x;

                alpineComponent.title = title;
                alpineComponent.form = form;
                alpineComponent.open = true;

                // Override deleteConfirmed to submit the form
                alpineComponent.deleteConfirmed = function() {
                    this.open = false;
                    this.form.submit();
                };

                return false; // Prevent default form submission
            }
        </script>
    </body>
</html>
