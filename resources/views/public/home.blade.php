@php
    $locale = app()->getLocale();
    $tier1 = collect($cities)->filter(fn ($c) => $c['tier'] === 1);
    $others = collect($cities)->filter(fn ($c) => $c['tier'] > 1);
@endphp

<x-public-layout :seo="$seo">

    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-hairline">
        <div class="aurora" aria-hidden="true"></div>
        <div class="absolute inset-0 mashrabiya opacity-30" aria-hidden="true"></div>
        <div data-network class="absolute inset-0 opacity-50" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-4xl px-4 py-20 text-center sm:px-6 sm:py-28">
            <p class="glass mb-6 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs text-ink-muted">
                <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                {{ __('marketing.home.eyebrow') }}
            </p>

            <h1 class="text-gradient text-4xl font-bold leading-[1.05] tracking-[-0.03em] sm:text-6xl">{{ __('marketing.home.h1') }}</h1>

            <p class="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">{{ __('marketing.home.lede') }}</p>
            <p class="mt-3 text-sm text-ink-faint">{{ __('marketing.home.sub') }}</p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('public.contact') }}" class="btn-gradient rounded-xl px-7 py-3.5 font-semibold text-accent-contrast">{{ __('marketing.common.cta_primary') }}</a>
                <a href="{{ route('public.services') }}" class="btn-ghost rounded-xl px-7 py-3.5 font-semibold">{{ __('marketing.common.cta_services') }}</a>
            </div>

            <dl class="mx-auto mt-14 grid max-w-2xl grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ([['1200', '+', 'workers', 'accent'], ['98', '%', 'compliance', 'gold'], [$cities ? count($cities) : 19, '', 'cities', 'teal'], ['24', '', 'response', 'accent']] as [$value, $suffix, $key, $tone])
                    <div class="glass-panel p-4">
                        <dt class="sr-only">{{ __("marketing.home.stats.$key") }}</dt>
                        <dd class="text-2xl font-bold tabular-nums text-{{ $tone }}" dir="ltr"><span x-data x-countup="{{ $value }}">{{ $value }}</span>{{ $suffix }}</dd>
                        <p class="mt-1 text-xs leading-tight text-ink-faint">{{ __("marketing.home.stats.$key") }}</p>
                    </div>
                @endforeach
            </dl>

            {{-- Product shot: real executive dashboard, framed with a glow. --}}
            <div class="relative mx-auto mt-16 max-w-5xl">
                <div class="hero-glow" aria-hidden="true"></div>
                <div class="hero-shot relative overflow-hidden p-1.5">
                    <img src="{{ asset('images/dashboard.svg') }}"
                         alt="{{ __('marketing.home.h1') }}"
                         class="block w-full rounded-[0.65rem]"
                         loading="eager" decoding="async"
                         onerror="this.closest('.hero-shot').parentElement.style.display='none'">
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6" id="services">
        <div class="max-w-3xl">
            <h2 class="text-2xl font-bold sm:text-3xl">{{ __('marketing.home.services_title') }}</h2>
            <p class="mt-3 text-ink-muted">{{ __('marketing.home.services_intro') }}</p>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $slug)
                @php($icon = config("company.services.{$slug}.icon", 'building'))
                <a href="{{ route('public.service', ['service' => $slug]) }}"
                   class="glass-panel group flex flex-col p-6 transition-colors hover:border-accent/50">
                    <x-icon :name="$icon" class="h-7 w-7 text-accent" />
                    <h3 class="mt-4 font-semibold group-hover:text-accent">{{ __("marketing.services.$slug.name") }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ __("marketing.services.$slug.short") }}</p>
                    <span class="mt-4 text-sm font-medium text-accent">{{ __('marketing.common.read_more') }} &rarr;</span>
                </a>
            @endforeach
        </div>

        <p class="mt-6">
            <a href="{{ route('public.services') }}" class="text-sm font-semibold text-accent">{{ __('marketing.common.all_services') }} &rarr;</a>
        </p>
    </section>

    {{-- Why supply over direct hiring --}}
    <section class="border-y border-hairline bg-surface-sunken">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
            <div class="max-w-3xl">
                <h2 class="text-2xl font-bold sm:text-3xl">{{ __('marketing.home.trust_title') }}</h2>
                <p class="mt-3 leading-relaxed text-ink-muted">{{ __('marketing.home.trust_intro') }}</p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach (__('marketing.home.benefits') as $benefit)
                    <div class="flex gap-3">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-accent-soft text-accent">
                            <x-icon name="check" class="h-4 w-4" />
                        </span>
                        <div>
                            <h3 class="font-semibold">{{ $benefit['title'] }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">{{ $benefit['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="max-w-3xl">
            <h2 class="text-2xl font-bold sm:text-3xl">{{ __('marketing.home.process_title') }}</h2>
            <p class="mt-3 text-ink-muted">{{ __('marketing.home.process_intro') }}</p>
        </div>

        <ol class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach (__('marketing.home.process') as $i => $step)
                <li class="glass-panel p-6">
                    <span class="text-sm font-bold tabular-nums text-accent">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-3 font-semibold">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- Sectors --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6">
        <h2 class="text-2xl font-bold sm:text-3xl">{{ __('marketing.home.sectors_title') }}</h2>
        <ul class="mt-6 flex flex-wrap gap-2">
            @foreach (__('marketing.home.sectors') as $sector)
                <li class="rounded-full border border-hairline px-4 py-2 text-sm text-ink-muted">{{ $sector }}</li>
            @endforeach
        </ul>
    </section>

    {{-- Locations --}}
    <section class="border-y border-hairline bg-surface-sunken" id="locations">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
            <div class="max-w-3xl">
                <h2 class="text-2xl font-bold sm:text-3xl">{{ __('marketing.home.locations_title') }}</h2>
                <p class="mt-3 text-ink-muted">{{ __('marketing.home.locations_intro') }}</p>
            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($tier1 as $slug => $city)
                    <a href="{{ route('public.city', ['city' => $slug]) }}" class="glass-panel flex items-center gap-2.5 p-4 transition-colors hover:border-accent/50">
                        <x-icon name="pin" class="h-5 w-5 shrink-0 text-accent" />
                        <span class="font-semibold">{{ $city[$locale] ?? $city['en'] }}</span>
                    </a>
                @endforeach
            </div>

            <ul class="mt-4 flex flex-wrap gap-2">
                @foreach ($others as $slug => $city)
                    <li><a href="{{ route('public.city', ['city' => $slug]) }}" class="inline-block rounded-full border border-hairline px-4 py-2 text-sm text-ink-muted transition-colors hover:border-accent hover:text-accent">{{ $city[$locale] ?? $city['en'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </section>

    @include('public.partials.faq', ['faqs' => $seo->faqs])

    @include('public.partials.cta', [
        'title' => __('marketing.home.cta_title'),
        'body' => __('marketing.home.cta_body'),
    ])

</x-public-layout>
