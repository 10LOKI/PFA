<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-mono text-[var(--chrome-text)] antialiased bg-[var(--void-bg)]">
        <!-- Perspective Grid Floor -->
        <div class="perspective-grid"></div>
        <div class="sun-glow"></div>

        <div class="min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 relative z-10">
            <!-- Logo -->
            <div class="mb-8">
                <a href="/" class="inline-block">
                    <x-application-logo class="w-24 h-24 fill-current text-[var(--neon-cyan)] drop-shadow-[0_0_15px_rgba(0,255,255,0.8)]" />
                </a>
            </div>

            <!-- Glass Panel Card -->
            <div class="w-full sm:max-w-md glass-panel rounded-lg p-8 border border-[var(--neon-cyan)] shadow-[0_0_20px_rgba(0,255,255,0.3)]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
