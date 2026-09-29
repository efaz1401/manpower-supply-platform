@php
    $locale = app()->getLocale();
    $dir = config("app.supported_locales.$locale.dir", 'ltr');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ASMC Manpower') }}</title>

        {{-- Theme before first paint --}}
        <script>
            (function () {
                try {
                    var t = localStorage.getItem('ds-theme') || 'dark';
                    if (t === 'light') document.documentElement.classList.add('light');
                } catch (e) {}
            })();
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700|figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-canvas text-ink overflow-x-hidden">
        <div class="relative min-h-screen flex flex-col grain">
            {{-- Ambient layers --}}
            <div class="aurora" aria-hidden="true"></div>
            <div class="absolute inset-0 mashrabiya opacity-40" aria-hidden="true"></div>

            {{-- Header --}}
            <header class="relative z-20 flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <x-application-logo class="h-9 w-9 text-accent" :animate="true" />
                    <span class="font-semibold">{{ __('ASMC Manpower') }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    @foreach (config('app.supported_locales') as $code => $meta)
                        <a href="{{ route('locale.switch', $code) }}"
                           class="{{ $locale === $code ? 'text-accent' : 'text-ink-muted hover:text-ink' }} transition-colors">{{ $meta['name'] }}</a>
                    @endforeach
                    @auth
                        <a href="{{ route('dashboard') }}" x-data x-magnetic class="px-4 py-2 bg-accent text-accent-contrast rounded-xl shadow-glow-accent">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" x-data x-magnetic class="px-4 py-2 bg-accent text-accent-contrast rounded-xl shadow-glow-accent">{{ __('Log in') }}</a>
                    @endauth
                </div>
            </header>

            {{-- Hero --}}
            <main class="relative z-10 flex-1 flex items-center">
                {{-- Interactive network scene (canvas injected by network.js; static under reduced-motion) --}}
                <div data-network class="absolute inset-0 z-0 opacity-70" aria-hidden="true"></div>

                <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
                    <p x-reveal class="inline-flex items-center gap-2 rounded-full glass px-3 py-1 text-xs text-ink-muted mb-6">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                        {{ __('Vision 2030 · Saudi workforce operations') }}
                    </p>

                    <h1 x-reveal class="text-4xl sm:text-6xl font-bold leading-tight tracking-tight">
                        {{ __('Manpower Management Platform') }}
                    </h1>

                    <p x-reveal="120" class="mt-6 text-lg text-ink-muted">
                        {{ __('Adel Saad Mater Contracting') }}
                    </p>
                    <p x-reveal="200" class="mt-3 text-sm text-ink-faint max-w-xl mx-auto">
                        {{ __('Workers, clients, deployments, compliance, payroll and invoicing — in one bilingual system.') }}
                    </p>

                    {{-- Live-feeling KPI strip --}}
                    <div x-reveal="300" class="mt-10 grid grid-cols-3 gap-4 max-w-lg mx-auto">
                        <div class="glass-panel p-4">
                            <p class="text-2xl font-bold text-accent tabular-nums"><span x-countup="1200">0</span>+</p>
                            <p class="mt-1 text-xs text-ink-faint">{{ __('Workers') }}</p>
                        </div>
                        <div class="glass-panel p-4">
                            <p class="text-2xl font-bold text-gold tabular-nums"><span x-countup="98">0</span>%</p>
                            <p class="mt-1 text-xs text-ink-faint">{{ __('Compliance') }}</p>
                        </div>
                        <div class="glass-panel p-4">
                            <p class="text-2xl font-bold text-teal tabular-nums"><span x-countup="24">0</span>/7</p>
                            <p class="mt-1 text-xs text-ink-faint">{{ __('Operations') }}</p>
                        </div>
                    </div>

                    <div x-reveal="400" class="mt-10">
                        @auth
                            <a href="{{ route('dashboard') }}" x-data x-magnetic class="inline-flex px-6 py-3 bg-accent text-accent-contrast rounded-xl shadow-glow-accent font-semibold">{{ __('Open Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" x-data x-magnetic class="inline-flex px-6 py-3 bg-accent text-accent-contrast rounded-xl shadow-glow-accent font-semibold">{{ __('Get Started') }}</a>
                        @endauth
                    </div>
                </div>
            </main>

            <footer class="relative z-10 px-6 py-4 text-center text-xs text-ink-faint">
                &copy; {{ date('Y') }} Adel Saad Mater Contracting — manpower.asmc.com.sa
            </footer>
        </div>

        {{-- Boots Livewire's bundled Alpine so `alpine:init` fires and x-reveal /
             x-countup / x-magnetic register. Without this the hero stays opacity:0. --}}
        @livewireScripts
    </body>
</html>
