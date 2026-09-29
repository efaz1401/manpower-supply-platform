@php
    $locale = app()->getLocale();
    $dir = config("app.supported_locales.$locale.dir", 'ltr');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- The authenticated platform holds no public content. Keeping it out
             of the index stops thin, auth-walled URLs from diluting the
             marketing site's crawl budget and topical focus. --}}
        <meta name="robots" content="noindex, nofollow">

        <title>{{ config('app.name', 'ASMC Manpower') }}</title>

        {{-- Set theme before first paint to avoid a flash of the wrong theme. --}}
        <script>
            (function () {
                try {
                    var t = localStorage.getItem('ds-theme') || 'dark';
                    if (t === 'light') document.documentElement.classList.add('light');
                } catch (e) {}
            })();
        </script>

        {{-- Fonts: Cairo (Arabic) + Figtree (Latin) --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700|figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-ink bg-canvas">
        <div class="relative min-h-screen flex overflow-hidden">
            {{-- Ambient aurora backdrop (paused under reduced-motion via CSS) --}}
            <div class="aurora" aria-hidden="true"></div>

            {{-- Sidebar --}}
            @include('layouts.partials.sidebar')

            <div class="relative z-10 flex-1 flex flex-col min-w-0">
                {{-- Top bar --}}
                @include('layouts.partials.topbar')

                {{-- Page Heading (optional slot) --}}
                @if (isset($header))
                    <header class="glass border-x-0 border-t-0">
                        <div class="mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts

        {{-- Global ⌘K command palette (role-filtered server-side) --}}
        @auth
            <x-command-palette />
        @endauth

        @stack('scripts')
    </body>
</html>
