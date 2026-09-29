@php
    $locale = app()->getLocale();
    $phone = config('company.phone');
    $phoneAlt = config('company.phone_alt');
    $whatsapp = config('company.whatsapp');
    $email = config('company.email');
    $lat = config('company.geo.lat');
    $lng = config('company.geo.lng');
    $addressLine = collect([
        config("company.address.street.$locale"),
        config("company.address.governorate.$locale"),
        config("company.address.region.$locale"),
        $locale === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia',
    ])->filter()->implode('، ');
@endphp

<x-public-layout :seo="$seo">

    @include('public.partials.breadcrumbs', ['crumbs' => $seo->breadcrumbs])

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
        <div class="max-w-3xl">
            <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ __('marketing.contact.h1') }}</h1>
            <p class="mt-4 text-lg leading-relaxed text-ink-muted">{{ __('marketing.contact.lede') }}</p>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <livewire:public.quote-request />
            </div>

            <aside class="space-y-4">
                <div class="glass-panel p-6">
                    <h2 class="font-semibold">{{ __('marketing.contact.channels_title') }}</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        @if ($phone)
                            <li class="flex items-center gap-3">
                                <x-icon name="phone" class="h-4 w-4 shrink-0 text-accent" />
                                <a href="tel:{{ $phone }}" dir="ltr" class="text-ink-muted hover:text-accent">{{ $phone }}</a>
                            </li>
                        @endif
                        @if ($phoneAlt)
                            <li class="flex items-center gap-3">
                                <x-icon name="phone" class="h-4 w-4 shrink-0 text-accent" />
                                <a href="tel:{{ $phoneAlt }}" dir="ltr" class="text-ink-muted hover:text-accent">{{ $phoneAlt }}</a>
                            </li>
                        @endif
                        @if ($email)
                            <li class="flex items-center gap-3">
                                <x-icon name="mail" class="h-4 w-4 shrink-0 text-accent" />
                                <a href="mailto:{{ $email }}" dir="ltr" class="text-ink-muted hover:text-accent">{{ $email }}</a>
                            </li>
                        @endif
                        <li class="flex items-start gap-3">
                            <x-icon name="pin" class="mt-0.5 h-4 w-4 shrink-0 text-accent" />
                            <span class="text-ink-muted">{{ $addressLine }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-accent" />
                            <span class="text-ink-muted">{{ __('marketing.contact.hours') }}</span>
                        </li>
                    </ul>

                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}" rel="nofollow noopener" target="_blank" class="mt-5 block rounded-xl border border-hairline px-4 py-2.5 text-center text-sm font-semibold text-ink transition-colors hover:border-accent hover:text-accent">{{ __('marketing.common.cta_whatsapp') }}</a>
                    @endif
                </div>

                {{-- Lazy-loaded so the map iframe never blocks LCP or drags the
                     mobile Core Web Vitals down. --}}
                <div class="glass-panel overflow-hidden">
                    <iframe
                        title="{{ __('marketing.nav.locations') }}"
                        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lng - 0.01 }},{{ $lat - 0.008 }},{{ $lng + 0.01 }},{{ $lat + 0.008 }}&layer=mapnik&marker={{ $lat }},{{ $lng }}"
                        class="h-64 w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="rounded-xl border border-warning/40 bg-warning/10 p-5">
                    <h2 class="text-sm font-semibold text-warning">{{ __('marketing.contact.note_title') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ __('marketing.contact.note') }}</p>
                </div>
            </aside>
        </div>
    </section>

</x-public-layout>
