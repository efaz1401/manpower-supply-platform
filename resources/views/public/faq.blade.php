<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <section class="mx-auto max-w-3xl px-4 pt-12 sm:px-6">
        <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.faq.h1') }}</h1>
        <p class="mt-4 text-lg leading-relaxed text-ink-muted">{{ __('marketing.faq.intro') }}</p>
    </section>

    @include('public.partials.faq', ['faqs' => $faqs, 'heading' => __('marketing.common.faq_title')])

    @include('public.partials.cta', [
        'title' => __('marketing.home.cta_title'),
        'body' => __('marketing.home.cta_body'),
    ])

</x-public-layout>
