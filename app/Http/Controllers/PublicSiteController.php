<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

/**
 * Serves the public, indexable marketing site.
 *
 * Copy lives in lang/{locale}/marketing.php rather than in ar.json because the
 * Arabic here is written natively for Saudi search intent, not translated from
 * the English — using the English sentence as a translation key would force the
 * two languages to stay word-for-word equivalent, which is exactly what we do
 * not want for SEO copy.
 */
class PublicSiteController extends Controller
{
    public function home(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.home.meta_title'),
            'description' => __('marketing.home.meta_description'),
            'faqs' => $this->faqItems(limit: 6),
            'extraSchema' => collect($this->featuredServices())
                ->map(fn ($slug) => Seo::serviceNode(
                    __("marketing.services.$slug.name"),
                    __("marketing.services.$slug.short"),
                    route('public.service', ['service' => $slug]),
                ))->values()->all(),
        ]);

        return view('public.home', [
            'seo' => $seo,
            'services' => $this->featuredServices(),
            'cities' => $this->cities(),
        ]);
    }

    public function services(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.services.index.meta_title'),
            'description' => __('marketing.services.index.meta_description'),
            'breadcrumbs' => $this->crumbs([__('marketing.nav.services') => route('public.services')]),
        ]);

        return view('public.services', [
            'seo' => $seo,
            'services' => array_keys(config('company.services')),
        ]);
    }

    public function service(string $locale, string $service): View
    {
        abort_unless(Arr::has(config('company.services'), $service), 404);

        $url = route('public.service', ['service' => $service]);

        $seo = Seo::make([
            'title' => __("marketing.services.$service.meta_title"),
            'description' => __("marketing.services.$service.meta_description"),
            'faqs' => $this->localFaqs("marketing.services.$service.faqs"),
            'breadcrumbs' => $this->crumbs([
                __('marketing.nav.services') => route('public.services'),
                __("marketing.services.$service.name") => $url,
            ]),
            'extraSchema' => [Seo::serviceNode(
                __("marketing.services.$service.name"),
                __("marketing.services.$service.short"),
                $url,
            )],
        ]);

        return view('public.service', [
            'seo' => $seo,
            'service' => $service,
            'cities' => $this->cities(tier: 2),
            'related' => collect(array_keys(config('company.services')))
                ->reject(fn ($s) => $s === $service)->take(4)->values()->all(),
        ]);
    }

    public function city(string $locale, string $city): View
    {
        $data = config("company.cities.$city");

        abort_unless($data, 404);

        $name = $data[$locale] ?? $data['en'];
        $nameIn = $locale === 'ar' ? $data['ar_in'] : $data['en'];
        $region = $locale === 'ar' ? $data['region_ar'] : $data['region_en'];
        $url = route('public.city', ['city' => $city]);

        $replace = ['city' => $name, 'city_in' => $nameIn, 'region' => $region];

        $seo = Seo::make([
            'title' => __('marketing.city.meta_title', $replace),
            'description' => __('marketing.city.meta_description', $replace),
            'faqs' => collect(__('marketing.city.faqs'))
                ->map(fn ($faq) => [
                    'q' => strtr($faq['q'], $this->braces($replace)),
                    'a' => strtr($faq['a'], $this->braces($replace)),
                ])->all(),
            'breadcrumbs' => $this->crumbs([
                __('marketing.nav.locations') => route('public.home').'#locations',
                $name => $url,
            ]),
            'extraSchema' => [
                Seo::serviceNode(
                    __('marketing.city.h1', $replace),
                    __('marketing.city.meta_description', $replace),
                    $url,
                    $name,
                ),
                [
                    '@type' => 'Place',
                    '@id' => $url.'#place',
                    'name' => $name,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $name,
                        'addressRegion' => $region,
                        'addressCountry' => 'SA',
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => $data['lat'],
                        'longitude' => $data['lng'],
                    ],
                ],
            ],
        ]);

        return view('public.city', [
            'seo' => $seo,
            'city' => $city,
            'cityData' => $data,
            'replace' => $replace,
            'services' => $this->featuredServices(),
            'nearby' => $this->nearbyCities($city, $data),
        ]);
    }

    public function ajeer(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.ajeer.meta_title'),
            'description' => __('marketing.ajeer.meta_description'),
            'faqs' => $this->localFaqs('marketing.ajeer.faqs'),
            'breadcrumbs' => $this->crumbs([__('marketing.nav.ajeer') => route('public.ajeer')]),
        ]);

        return view('public.ajeer', ['seo' => $seo]);
    }

    public function about(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.about.meta_title'),
            'description' => __('marketing.about.meta_description'),
            'breadcrumbs' => $this->crumbs([__('marketing.nav.about') => route('public.about')]),
            'extraSchema' => [[
                '@type' => 'AboutPage',
                '@id' => route('public.about').'#about',
                'mainEntity' => ['@id' => Seo::organizationId()],
            ]],
        ]);

        return view('public.about', ['seo' => $seo]);
    }

    public function faq(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.faq.meta_title'),
            'description' => __('marketing.faq.meta_description'),
            'faqs' => $this->faqItems(),
            'breadcrumbs' => $this->crumbs([__('marketing.nav.faq') => route('public.faq')]),
        ]);

        return view('public.faq', ['seo' => $seo, 'faqs' => $this->faqItems()]);
    }

    public function contact(): View
    {
        $seo = Seo::make([
            'title' => __('marketing.contact.meta_title'),
            'description' => __('marketing.contact.meta_description'),
            'breadcrumbs' => $this->crumbs([__('marketing.nav.contact') => route('public.contact')]),
            'extraSchema' => [[
                '@type' => 'ContactPage',
                '@id' => route('public.contact').'#contact',
                'mainEntity' => ['@id' => Seo::organizationId()],
            ]],
        ]);

        return view('public.contact', ['seo' => $seo]);
    }

    public function thankYou(): View
    {
        return view('public.thank-you', [
            'seo' => Seo::make([
                'title' => __('marketing.thanks.meta_title'),
                'description' => __('marketing.thanks.meta_description'),
                'noindex' => true,
            ]),
        ]);
    }

    /*
    |----------------------------------------------------------------------
    | Helpers
    |----------------------------------------------------------------------
    */

    /** @return array<int, string> */
    protected function featuredServices(): array
    {
        return collect(config('company.services'))
            ->filter(fn ($meta) => $meta['featured'])
            ->keys()->all();
    }

    /** @return array<string, array<string, mixed>> */
    protected function cities(?int $tier = null): array
    {
        return collect(config('company.cities'))
            ->when($tier, fn ($c) => $c->filter(fn ($city) => $city['tier'] <= $tier))
            ->all();
    }

    /** Cities in the same region first, then anything else, capped at 5. */
    protected function nearbyCities(string $current, array $data): array
    {
        return collect(config('company.cities'))
            ->except($current)
            ->sortByDesc(fn ($c) => $c['region_en'] === $data['region_en'] ? 1 : 0)
            ->take(5)
            ->all();
    }

    /** @return array<int, array{q: string, a: string}> */
    protected function faqItems(?int $limit = null): array
    {
        $items = collect(__('marketing.faq.items'));

        return ($limit ? $items->take($limit) : $items)->values()->all();
    }

    /** @return array<int, array{q: string, a: string}> */
    protected function localFaqs(string $key): array
    {
        $faqs = __($key);

        return is_array($faqs) ? array_values($faqs) : [];
    }

    /**
     * Prepends the home crumb so every breadcrumb trail is rooted.
     *
     * @param  array<string, string>  $trail  name => url
     * @return array<int, array{name: string, url: string}>
     */
    protected function crumbs(array $trail): array
    {
        $crumbs = [['name' => __('marketing.nav.home'), 'url' => route('public.home')]];

        foreach ($trail as $name => $url) {
            $crumbs[] = ['name' => $name, 'url' => $url];
        }

        return $crumbs;
    }

    /** Wraps replacement keys in Laravel's `:key` / `{key}` placeholder form. */
    protected function braces(array $replace): array
    {
        $out = [];

        foreach ($replace as $key => $value) {
            $out[":$key"] = $value;
        }

        return $out;
    }
}
