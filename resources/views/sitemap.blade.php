{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($entries as $entry)
@foreach ($locales as $locale)
    <url>
        <loc>{{ $entry['urls'][$locale] }}</loc>
@foreach ($entry['urls'] as $altLocale => $altUrl)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale === 'ar' ? 'ar-SA' : $altLocale }}" href="{{ $altUrl }}"/>
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $entry['urls'][config('app.locale')] ?? reset($entry['urls']) }}"/>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>{{ $entry['changefreq'] }}</changefreq>
        <priority>{{ $entry['priority'] }}</priority>
    </url>
@endforeach
@endforeach
</urlset>
