<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
        <div class="max-w-3xl">
            <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.services.index.h1') }}</h1>
            <p class="mt-4 text-lg leading-relaxed text-ink-muted">{{ __('marketing.services.index.intro') }}</p>
        </div>

        <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $slug)
                <a href="{{ route('public.service', ['service' => $slug]) }}"
                   class="glass-panel group flex flex-col p-6 transition-colors hover:border-accent/50">
                    <x-icon :name="config(\"company.services.$slug.icon\")" class="h-7 w-7 text-accent" />
                    <h2 class="mt-4 font-semibold group-hover:text-accent">{{ __("marketing.services.$slug.name") }}</h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ __("marketing.services.$slug.short") }}</p>
                    <span class="mt-4 text-sm font-medium text-accent">{{ __('marketing.common.read_more') }} &rarr;</span>
                </a>
            @endforeach
        </div>
    </section>

    @include('public.partials.cta', [
        'title' => __('marketing.home.cta_title'),
        'body' => __('marketing.home.cta_body'),
    ])

</x-public-layout>
