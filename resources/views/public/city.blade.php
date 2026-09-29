@php
    $locale = app()->getLocale();
    $cityName = $cityData[$locale] ?? $cityData['en'];
@endphp

<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <article>
        <header class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
            <div class="max-w-3xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-hairline px-3 py-1 text-xs text-ink-muted">
                    <x-icon name="pin" class="h-3.5 w-3.5 text-accent" />
                    {{ $replace['region'] }}
                </p>
                <h1 class="mt-5 text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.city.h1', $replace) }}</h1>
                <p class="mt-5 text-lg leading-relaxed text-ink-muted">{{ __('marketing.city.intro', $replace) }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('public.contact') }}" class="rounded-xl bg-accent px-5 py-2.5 text-sm font-semibold text-accent-contrast shadow-glow-accent">{{ __('marketing.common.cta_primary') }}</a>
                    @if ($whatsapp = config('company.whatsapp'))
                        <a href="https://wa.me/{{ $whatsapp }}" rel="nofollow noopener" target="_blank" class="rounded-xl border border-hairline px-5 py-2.5 text-sm font-semibold text-ink transition-colors hover:border-accent hover:text-accent">{{ __('marketing.common.cta_whatsapp') }}</a>
                    @endif
                </div>
            </div>
        </header>

        <section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6">
            <h2 class="text-2xl font-bold">{{ __('marketing.city.services_title', $replace) }}</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $slug)
                    <a href="{{ route('public.service', ['service' => $slug]) }}" class="glass-panel group flex flex-col p-6 transition-colors hover:border-accent/50">
                        <x-icon :name="config(\"company.services.$slug.icon\")" class="h-6 w-6 text-accent" />
                        <h3 class="mt-3 font-semibold group-hover:text-accent">{{ __("marketing.services.$slug.name") }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ __("marketing.services.$slug.short") }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="border-y border-hairline bg-surface-sunken">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
                <h2 class="text-2xl font-bold">{{ __('marketing.city.why_title', $replace) }}</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach (__('marketing.city.why') as $item)
                        <div>
                            <h3 class="font-semibold">{{ strtr($item['h'], [':city' => $replace['city'], ':city_in' => $replace['city_in'], ':region' => $replace['region']]) }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ strtr($item['p'], [':city' => $replace['city'], ':city_in' => $replace['city_in'], ':region' => $replace['region']]) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 max-w-3xl">
                    <h2 class="text-xl font-bold">{{ __('marketing.city.coverage_title') }}</h2>
                    <p class="mt-3 leading-relaxed text-ink-muted">{{ __('marketing.city.coverage', $replace) }}</p>
                </div>
            </div>
        </section>
    </article>

    @include('public.partials.faq', ['faqs' => $seo->faqs])

    <section class="mx-auto max-w-7xl px-4 pb-4 sm:px-6">
        <h2 class="text-xl font-bold">{{ __('marketing.common.nearby_cities') }}</h2>
        <ul class="mt-5 flex flex-wrap gap-2">
            @foreach ($nearby as $slug => $near)
                <li><a href="{{ route('public.city', ['city' => $slug]) }}" class="inline-block rounded-full border border-hairline px-4 py-2 text-sm text-ink-muted transition-colors hover:border-accent hover:text-accent">{{ $near[$locale] ?? $near['en'] }}</a></li>
            @endforeach
        </ul>
    </section>

    @include('public.partials.cta', [
        'title' => __('marketing.city.cta_title', $replace),
        'body' => __('marketing.city.cta_body', $replace),
    ])

</x-public-layout>
