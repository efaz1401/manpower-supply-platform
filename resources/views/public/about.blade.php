@php
    $locale = app()->getLocale();
    $cr = config('company.cr_number');
    $addressLine = collect([
        config("company.address.street.$locale"),
        config("company.address.governorate.$locale"),
        config("company.address.region.$locale"),
        $locale === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia',
    ])->filter()->implode('، ');
@endphp

<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6">
        <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.about.h1') }}</h1>
        <p class="mt-5 text-lg leading-relaxed text-ink-muted">{{ __('marketing.about.lede') }}</p>

        {{-- Verifiable identity block. For a manpower supplier the CR number
             and a real physical address are the primary E-E-A-T signals a
             Saudi buyer (and Google) checks before trusting the business. --}}
        <dl class="glass-panel mt-8 grid gap-4 p-6 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase tracking-wide text-ink-faint">{{ __('marketing.footer.cr') }}</dt>
                <dd class="mt-1 font-semibold" dir="ltr">{{ $cr ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-ink-faint">{{ __('marketing.footer.contact') }}</dt>
                <dd class="mt-1 font-semibold" dir="ltr">{{ config('company.phone') }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-ink-faint">{{ __('marketing.nav.locations') }}</dt>
                <dd class="mt-1 text-sm text-ink-muted">{{ $addressLine }}</dd>
            </div>
        </dl>

        @foreach (__('marketing.about.sections') as $section)
            <section class="mt-9">
                <h2 class="text-xl font-bold sm:text-2xl">{{ $section['h'] }}</h2>
                <p class="mt-3 leading-relaxed text-ink-muted">{{ $section['p'] }}</p>
            </section>
        @endforeach

        <section class="mt-12">
            <h2 class="text-xl font-bold sm:text-2xl">{{ __('marketing.about.values_title') }}</h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-3">
                @foreach (__('marketing.about.values') as $value)
                    <div class="glass-panel p-5">
                        <h3 class="font-semibold">{{ $value['h'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $value['p'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </article>

    @include('public.partials.cta', [
        'title' => __('marketing.home.cta_title'),
        'body' => __('marketing.home.cta_body'),
    ])

</x-public-layout>
