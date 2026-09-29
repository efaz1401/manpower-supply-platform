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
        {{-- Auth screens are app surface, not marketing surface. --}}
        <meta name="robots" content="noindex, nofollow">
        <title>{{ config('app.name', 'ASMC Manpower') }}</title>

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
    <body class="font-sans text-ink antialiased bg-canvas">
        <div class="min-h-screen grid lg:grid-cols-2">
            {{-- Showcase side (hidden on small screens) --}}
            <div class="relative hidden lg:flex flex-col justify-between p-10 overflow-hidden grain">
                <div class="aurora" aria-hidden="true"></div>
                <div class="absolute inset-0 mashrabiya opacity-40" aria-hidden="true"></div>
                <div data-network class="absolute inset-0 opacity-70" aria-hidden="true"></div>

                <div class="relative z-10 flex items-center gap-2.5">
                    <x-application-logo class="h-9 w-9 text-accent" :animate="true" />
                    <span class="font-semibold">{{ __('ASMC Manpower') }}</span>
                </div>
                <div class="relative z-10 max-w-md">
                    <h2 class="text-3xl font-bold leading-tight">{{ __('Manpower Management Platform') }}</h2>
                    <p class="mt-3 text-ink-muted">{{ __('Workers, clients, deployments, compliance, payroll and invoicing — in one bilingual system.') }}</p>
                </div>
                <p class="relative z-10 text-xs text-ink-faint">&copy; {{ date('Y') }} Adel Saad Mater Contracting</p>
            </div>

            {{-- Form side --}}
            <div class="flex flex-col justify-center items-center px-6 py-10">
                <div class="w-full sm:max-w-md">
                    <div class="flex items-center justify-between mb-6">
                        <a href="/" wire:navigate class="lg:hidden flex items-center gap-2">
                            <x-application-logo class="h-8 w-8 text-accent" />
                            <span class="font-semibold">{{ __('ASMC Manpower') }}</span>
                        </a>
                        <div class="flex items-center gap-3 text-sm ms-auto">
                            @foreach (config('app.supported_locales') as $code => $meta)
                                <a href="{{ route('locale.switch', $code) }}"
                                   class="{{ $locale === $code ? 'text-accent' : 'text-ink-faint hover:text-ink' }} transition-colors">{{ $meta['name'] }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-panel px-6 py-6 animate-scale-in">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
