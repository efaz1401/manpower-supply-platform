@php
    $locale = app()->getLocale();
    $key = "marketing.services.$service";
@endphp

<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <article>
        <header class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
            <div class="max-w-3xl">
                <x-icon :name="config(\"company.services.$service.icon\")" class="h-9 w-9 text-accent" />
                <h1 class="mt-5 text-3xl font-bold leading-tight sm:text-4xl">{{ __("$key.h1") }}</h1>
                <p class="mt-5 text-lg leading-relaxed text-ink-muted">{{ __("$key.intro") }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('public.contact') }}" class="rounded-xl bg-accent px-5 py-2.5 text-sm font-semibold text-accent-contrast shadow-glow-accent">{{ __('marketing.common.cta_primary') }}</a>
                    @if ($phone = config('company.phone'))
                        <a href="tel:{{ $phone }}" dir="ltr" class="rounded-xl border border-hairline px-5 py-2.5 text-sm font-semibold text-ink transition-colors hover:border-accent hover:text-accent">{{ $phone }}</a>
                    @endif
                </div>
            </div>
        </header>

        <div class="mx-auto grid max-w-7xl gap-12 px-4 pb-16 sm:px-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @foreach (__("$key.sections") as $section)
                    <section class="mb-9">
                        <h2 class="text-xl font-bold sm:text-2xl">{{ $section['h'] }}</h2>
                        <p class="mt-3 leading-relaxed text-ink-muted">{{ $section['p'] }}</p>
                    </section>
                @endforeach
            </div>

            <aside class="lg:col-span-1">
                <div class="glass-panel p-6 lg:sticky lg:top-24">
                    <h2 class="font-semibold">{{ __('marketing.common.roles_title') }}</h2>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach (__("$key.roles") as $role)
                            <li class="flex items-start gap-2 text-ink-muted">
                                <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-accent" />
                                <span>{{ $role }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('public.contact') }}" class="mt-6 block rounded-xl bg-accent px-4 py-2.5 text-center text-sm font-semibold text-accent-contrast">{{ __('marketing.common.cta_primary') }}</a>
                </div>
            </aside>
        </div>
    </article>

    {{-- Service × city internal links. This grid is what actually builds
         topical coverage: each service page links to the matching city pages
         and vice versa, so the cluster is crawlable in both directions. --}}
    <section class="border-y border-hairline bg-surface-sunken">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
            <h2 class="text-xl font-bold">{{ __('marketing.common.available_in') }}</h2>
            <ul class="mt-5 flex flex-wrap gap-2">
                @foreach ($cities as $slug => $city)
                    <li><a href="{{ route('public.city', ['city' => $slug]) }}" class="inline-block rounded-full border border-hairline px-4 py-2 text-sm text-ink-muted transition-colors hover:border-accent hover:text-accent">{{ $city[$locale] ?? $city['en'] }}</a></li>
                @endforeach
                <li><a href="{{ route('public.home') }}#locations" class="inline-block rounded-full border border-accent/40 px-4 py-2 text-sm font-medium text-accent">{{ __('marketing.common.all_cities') }}</a></li>
            </ul>
        </div>
    </section>

    @include('public.partials.faq', ['faqs' => $seo->faqs])

    <section class="mx-auto max-w-7xl px-4 pb-4 sm:px-6">
        <h2 class="text-xl font-bold">{{ __('marketing.common.related_services') }}</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $slug)
                <a href="{{ route('public.service', ['service' => $slug]) }}" class="glass-panel p-5 transition-colors hover:border-accent/50">
                    <x-icon :name="config(\"company.services.$slug.icon\")" class="h-6 w-6 text-accent" />
                    <h3 class="mt-3 text-sm font-semibold">{{ __("marketing.services.$slug.name") }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    @include('public.partials.cta', [
        'title' => __('marketing.home.cta_title'),
        'body' => __('marketing.home.cta_body'),
    ])

</x-public-layout>
