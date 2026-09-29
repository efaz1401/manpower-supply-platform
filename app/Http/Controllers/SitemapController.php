<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

/**
 * Generates sitemap.xml and llms.txt from the route/config definitions so they
 * can never drift out of sync with the actual pages.
 *
 * The sitemap embeds xhtml:link alternates for every URL — that is Google's
 * documented way to declare hreflang from a sitemap, and it is more reliable
 * than head tags alone for a site with this many locale pairs.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $locales = array_keys(config('app.supported_locales'));

        $xml = view('sitemap', [
            'entries' => $this->entries($locales),
            'locales' => $locales,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * A plain-text site summary for AI answer engines. Not a ranking factor,
     * but it gives crawlers that respect it a clean map of what we publish and
     * the exact terminology we want quoted back.
     */
    public function llms(): Response
    {
        $original = App::getLocale();
        App::setLocale('en');

        $text = view('llms', [
            'services' => array_keys(config('company.services')),
            'cities' => config('company.cities'),
        ])->render();

        App::setLocale($original);

        return response($text, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * Every indexable page, grouped so each entry knows its alternates.
     *
     * @return array<int, array{urls: array<string, string>, priority: string, changefreq: string}>
     */
    protected function entries(array $locales): array
    {
        $pages = [
            ['route' => 'public.home', 'params' => [], 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['route' => 'public.services', 'params' => [], 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['route' => 'public.ajeer', 'params' => [], 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['route' => 'public.about', 'params' => [], 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['route' => 'public.faq', 'params' => [], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'public.contact', 'params' => [], 'priority' => '0.8', 'changefreq' => 'yearly'],
        ];

        foreach (array_keys(config('company.services')) as $service) {
            $pages[] = ['route' => 'public.service', 'params' => ['service' => $service], 'priority' => '0.9', 'changefreq' => 'monthly'];
        }

        foreach (config('company.cities') as $slug => $city) {
            $pages[] = [
                'route' => 'public.city',
                'params' => ['city' => $slug],
                // Tier-1 cities carry the commercial intent we most want indexed.
                'priority' => $city['tier'] === 1 ? '0.9' : ($city['tier'] === 2 ? '0.8' : '0.7'),
                'changefreq' => 'monthly',
            ];
        }

        return collect($pages)->map(fn ($page) => [
            'urls' => collect($locales)
                ->mapWithKeys(fn ($locale) => [
                    $locale => route($page['route'], array_merge($page['params'], ['locale' => $locale])),
                ])->all(),
            'priority' => $page['priority'],
            'changefreq' => $page['changefreq'],
        ])->all();
    }
}
