@php
    $locale = app()->getLocale();
    $dir = config("app.supported_locales.$locale.dir", 'ltr');
    $alternates = $seo->alternates();
    $siteName = config("company.legal_name.$locale");
@endphp
<!DOCTYPE html>
<html lang="{{ $locale === 'ar' ? 'ar-SA' : 'en' }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}">
    <link rel="canonical" href="{{ $seo->canonicalUrl() }}">

    @if ($seo->noindex)
        <meta name="robots" content="noindex, follow">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    @endif

    {{-- hreflang: every locale points at every other, plus a self-reference and
         an x-default. Arabic is x-default because Saudi Arabia is the only
         market served. --}}
    @foreach ($alternates as $code => $url)
        <link rel="alternate" hreflang="{{ $code === 'ar' ? 'ar-SA' : $code }}" href="{{ $url }}">
    @endforeach
    @if (isset($alternates['ar']))
        <link rel="alternate" hreflang="x-default" href="{{ $alternates['ar'] }}">
    @endif

    {{-- Local signals. Legacy tags, but still read by a number of the local
         directories and citation aggregators that feed the map pack. --}}
    <meta name="geo.region" content="SA-04">
    <meta name="geo.placename" content="{{ config("company.address.city.$locale") }}">
    <meta name="geo.position" content="{{ config('company.geo.lat') }};{{ config('company.geo.lng') }}">
    <meta name="ICBM" content="{{ config('company.geo.lat') }}, {{ config('company.geo.lng') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:url" content="{{ $seo->canonicalUrl() }}">
    <meta property="og:locale" content="{{ $locale === 'ar' ? 'ar_SA' : 'en_US' }}">
    @foreach ($alternates as $code => $url)
        @if ($code !== $locale)
            <meta property="og:locale:alternate" content="{{ $code === 'ar' ? 'ar_SA' : 'en_US' }}">
        @endif
    @endforeach
    @if ($seo->image)
        <meta property="og:image" content="{{ $seo->image }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->title }}">
    <meta name="twitter:description" content="{{ $seo->description }}">

    <meta name="theme-color" content="#0B1220">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    <script>
        (function () {
            try {
                var t = localStorage.getItem('ds-theme') || 'dark';
                if (t === 'light') document.documentElement.classList.add('light');
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">{!! json_encode($seo->schemaGraph(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
</head>
<body class="font-sans antialiased bg-canvas text-ink">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:start-3 focus:z-50 focus:rounded-lg focus:bg-accent focus:px-4 focus:py-2 focus:text-accent-contrast">
        {{ __('marketing.common.skip') }}
    </a>

    @include('public.partials.header', ['alternates' => $alternates])

    <main id="main">
        {{ $slot }}
    </main>

    @include('public.partials.footer')

    @livewireScripts
</body>
</html>
