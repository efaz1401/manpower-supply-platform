@php
    $locale = app()->getLocale();
    $phone = config('company.phone');
    $nav = [
        ['label' => __('marketing.nav.services'), 'url' => route('public.services')],
        ['label' => __('marketing.nav.ajeer'), 'url' => route('public.ajeer')],
        ['label' => __('marketing.nav.about'), 'url' => route('public.about')],
        ['label' => __('marketing.nav.faq'), 'url' => route('public.faq')],
    ];
@endphp

<header x-data="{ open: false }" class="glass-nav sticky top-0 z-40">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6">
        <a href="{{ route('public.home') }}" class="flex shrink-0 items-center gap-2.5">
            <x-application-logo class="h-9 w-9 text-accent" />
            <span class="text-sm font-bold leading-tight sm:text-base">{{ config("company.short_name.$locale") }}</span>
        </a>

        <nav aria-label="{{ __('marketing.nav.home') }}" class="ms-auto hidden items-center gap-6 text-sm lg:flex">
            @foreach ($nav as $item)
                <a href="{{ $item['url'] }}"
                   @class(['transition-colors hover:text-accent', 'text-accent' => request()->url() === $item['url'], 'text-ink-muted' => request()->url() !== $item['url']])>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="ms-auto flex items-center gap-2 lg:ms-0">
            {{-- Language switch links straight to the hreflang alternate so the
                 swap is a real, crawlable URL rather than a session toggle. --}}
            <div class="hidden items-center gap-1 text-xs sm:flex">
                @foreach ($alternates as $code => $url)
                    <a href="{{ $url }}" hreflang="{{ $code === 'ar' ? 'ar-SA' : $code }}"
                       @class(['rounded-lg px-2 py-1 transition-colors', 'bg-accent-soft text-accent font-semibold' => $code === $locale, 'text-ink-faint hover:text-ink' => $code !== $locale])>{{ config("app.supported_locales.$code.name") }}</a>
                @endforeach
            </div>

            @if ($phone)
                <a href="tel:{{ $phone }}" class="hidden rounded-xl border border-hairline px-3 py-2 text-sm text-ink-muted transition-colors hover:border-accent hover:text-accent md:inline-flex" dir="ltr">{{ $phone }}</a>
            @endif

            <a href="{{ route('public.contact') }}" class="btn-gradient rounded-xl px-4 py-2 text-sm font-semibold text-accent-contrast">{{ __('marketing.common.cta_primary') }}</a>

            <button type="button" @click="open = !open" class="rounded-lg p-2 text-ink-muted lg:hidden" :aria-expanded="open" aria-controls="mobile-nav" aria-label="{{ __('marketing.nav.home') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" x-show="!open" d="M4 7h16M4 12h16M4 17h16"/>
                    <path stroke-linecap="round" x-show="open" x-cloak d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav" x-show="open" x-cloak x-collapse class="border-t border-hairline lg:hidden">
        <nav class="mx-auto max-w-7xl px-4 py-3 sm:px-6">
            @foreach ($nav as $item)
                <a href="{{ $item['url'] }}" class="block rounded-lg px-2 py-2.5 text-sm text-ink-muted hover:bg-surface hover:text-ink">{{ $item['label'] }}</a>
            @endforeach
            <div class="mt-2 flex items-center gap-2 border-t border-hairline pt-3 text-xs">
                @foreach ($alternates as $code => $url)
                    <a href="{{ $url }}" hreflang="{{ $code === 'ar' ? 'ar-SA' : $code }}"
                       @class(['rounded-lg px-2 py-1', 'bg-accent-soft text-accent font-semibold' => $code === $locale, 'text-ink-faint' => $code !== $locale])>{{ config("app.supported_locales.$code.name") }}</a>
                @endforeach
            </div>
        </nav>
    </div>
</header>
