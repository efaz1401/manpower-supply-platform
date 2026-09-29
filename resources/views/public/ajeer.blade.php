<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6">
        <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.ajeer.h1') }}</h1>
        <p class="mt-5 text-lg leading-relaxed text-ink-muted">{{ __('marketing.ajeer.lede') }}</p>

        {{-- Jump list: gives the page an on-page table of contents, which is
             both a usability win and the structure AI answer engines prefer
             when they lift a passage. --}}
        <nav class="glass-panel mt-8 p-5" aria-label="{{ __('marketing.common.quick_facts') }}">
            <ol class="space-y-1.5 text-sm">
                @foreach (__('marketing.ajeer.sections') as $i => $section)
                    <li><a href="#ajeer-{{ $i }}" class="text-ink-muted transition-colors hover:text-accent">{{ $section['h'] }}</a></li>
                @endforeach
            </ol>
        </nav>

        @foreach (__('marketing.ajeer.sections') as $i => $section)
            <section id="ajeer-{{ $i }}" class="mt-9 scroll-mt-24">
                <h2 class="text-xl font-bold sm:text-2xl">{{ $section['h'] }}</h2>
                <p class="mt-3 leading-relaxed text-ink-muted">{{ $section['p'] }}</p>
            </section>
        @endforeach
    </article>

    @include('public.partials.faq', ['faqs' => $seo->faqs])

    @include('public.partials.cta', [
        'title' => __('marketing.ajeer.cta_title'),
        'body' => __('marketing.ajeer.cta_body'),
    ])

</x-public-layout>
