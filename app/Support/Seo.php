<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Builds the per-page SEO payload (meta tags, hreflang alternates) and the
 * JSON-LD graph for the public marketing site.
 *
 * Schema is emitted as a single `@graph` so every node can cross-reference the
 * organisation by `@id` instead of repeating it — that's what lets Google (and
 * the AI answer engines) resolve "who published this page" on every URL.
 */
class Seo
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public ?string $canonical = null,
        public ?string $image = null,
        public bool $noindex = false,
        /** @var array<int, array{name: string, url: string}> */
        public array $breadcrumbs = [],
        /** @var array<int, array{q: string, a: string}> */
        public array $faqs = [],
        /** @var array<int, array<string, mixed>> extra JSON-LD nodes */
        public array $extraSchema = [],
    ) {}

    public static function make(array $attributes = []): self
    {
        return new self(...$attributes);
    }

    /*
    |----------------------------------------------------------------------
    | URLs
    |----------------------------------------------------------------------
    */

    public function canonicalUrl(): string
    {
        return $this->canonical ?? url()->current();
    }

    /**
     * The same page in every supported locale, keyed by locale code.
     *
     * @return array<string, string>
     */
    public function alternates(): array
    {
        $route = Route::current();

        if (! $route || ! $route->getName()) {
            return [];
        }

        $alternates = [];

        foreach (array_keys(config('app.supported_locales')) as $code) {
            $alternates[$code] = route(
                $route->getName(),
                array_merge($route->parameters(), ['locale' => $code])
            );
        }

        return $alternates;
    }

    /*
    |----------------------------------------------------------------------
    | JSON-LD
    |----------------------------------------------------------------------
    */

    public function schemaGraph(): array
    {
        $graph = array_values(array_filter([
            $this->organizationNode(),
            $this->websiteNode(),
            $this->webPageNode(),
            $this->breadcrumbNode(),
            $this->faqNode(),
        ]));

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_merge($graph, $this->extraSchema),
        ];
    }

    public static function organizationId(): string
    {
        return rtrim(config('app.url'), '/').'/#organization';
    }

    public static function websiteId(): string
    {
        return rtrim(config('app.url'), '/').'/#website';
    }

    /**
     * EmploymentAgency is the correct LocalBusiness subtype for a manpower
     * supplier — it is more specific than plain LocalBusiness and maps to how
     * Google classifies staffing/labour-supply firms.
     */
    protected function organizationNode(): array
    {
        $locale = app()->getLocale();
        $address = config('company.address');

        $node = [
            '@type' => ['Organization', 'EmploymentAgency'],
            '@id' => self::organizationId(),
            'name' => config("company.legal_name.$locale"),
            'alternateName' => array_values(array_unique(array_filter([
                config('company.legal_name.ar'),
                config('company.legal_name.en'),
                config('company.short_name.en'),
            ]))),
            'url' => rtrim(config('app.url'), '/').'/',
            'description' => __('marketing.org.description'),
            'foundingDate' => config('company.founded'),
            'areaServed' => [
                '@type' => 'Country',
                'name' => $locale === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia',
                'identifier' => 'SA',
            ],
            'knowsLanguage' => ['ar-SA', 'en'],
        ];

        if ($logo = $this->logoUrl()) {
            $node['logo'] = ['@type' => 'ImageObject', '@id' => rtrim(config('app.url'), '/').'/#logo', 'url' => $logo];
            $node['image'] = ['@id' => rtrim(config('app.url'), '/').'/#logo'];
        }

        $postal = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => data_get($address, "street.$locale"),
            'addressLocality' => data_get($address, "city.$locale"),
            'addressRegion' => data_get($address, "region.$locale"),
            'postalCode' => data_get($address, 'postal_code'),
            'addressCountry' => 'SA',
        ]);

        // Only publish an address node once a real street is configured —
        // a half-filled PostalAddress is worse than none for local trust.
        if (data_get($address, "street.$locale")) {
            $node['address'] = $postal;
            $node['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => config('company.geo.lat'),
                'longitude' => config('company.geo.lng'),
            ];
            $node['openingHoursSpecification'] = collect(config('company.opening_hours'))
                ->map(fn ($slot) => [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => $slot['days'],
                    'opens' => $slot['opens'],
                    'closes' => $slot['closes'],
                ])->all();
        }

        if ($phone = config('company.phone')) {
            $node['telephone'] = $phone;
            $node['contactPoint'] = [[
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'sales',
                'areaServed' => 'SA',
                'availableLanguage' => ['Arabic', 'English'],
            ]];
        }

        if ($email = config('company.email')) {
            $node['email'] = $email;
        }

        if ($cr = config('company.cr_number')) {
            $node['identifier'][] = ['@type' => 'PropertyValue', 'name' => 'CR', 'value' => $cr];
        }

        if ($vat = config('company.vat_number')) {
            $node['vatID'] = $vat;
        }

        $sameAs = array_values(array_filter(array_merge(
            array_values(config('company.social', [])),
            [config('company.gbp_url')]
        )));

        if ($sameAs) {
            $node['sameAs'] = $sameAs;
        }

        return $node;
    }

    protected function websiteNode(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => self::websiteId(),
            'url' => rtrim(config('app.url'), '/').'/',
            'name' => config('company.legal_name.'.app()->getLocale()),
            'publisher' => ['@id' => self::organizationId()],
            'inLanguage' => app()->getLocale() === 'ar' ? 'ar-SA' : 'en',
        ];
    }

    protected function webPageNode(): array
    {
        return array_filter([
            '@type' => 'WebPage',
            '@id' => $this->canonicalUrl().'#webpage',
            'url' => $this->canonicalUrl(),
            'name' => $this->title,
            'description' => $this->description,
            'isPartOf' => ['@id' => self::websiteId()],
            'about' => ['@id' => self::organizationId()],
            'inLanguage' => app()->getLocale() === 'ar' ? 'ar-SA' : 'en',
        ]);
    }

    protected function breadcrumbNode(): ?array
    {
        if (count($this->breadcrumbs) < 2) {
            return null;
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $this->canonicalUrl().'#breadcrumb',
            'itemListElement' => collect($this->breadcrumbs)->values()->map(fn ($crumb, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ])->all(),
        ];
    }

    protected function faqNode(): ?array
    {
        if (! $this->faqs) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            '@id' => $this->canonicalUrl().'#faq',
            'mainEntity' => collect($this->faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ])->all(),
        ];
    }

    protected function logoUrl(): ?string
    {
        return file_exists(public_path('images/logo.png'))
            ? asset('images/logo.png')
            : null;
    }

    /*
    |----------------------------------------------------------------------
    | Reusable service / offer nodes
    |----------------------------------------------------------------------
    */

    public static function serviceNode(string $name, string $description, string $url, ?string $areaName = null): array
    {
        return array_filter([
            '@type' => 'Service',
            '@id' => $url.'#service',
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'serviceType' => $name,
            'provider' => ['@id' => self::organizationId()],
            'areaServed' => [
                '@type' => $areaName ? 'City' : 'Country',
                'name' => $areaName ?: (app()->getLocale() === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia'),
            ],
            'audience' => ['@type' => 'BusinessAudience', 'name' => __('marketing.org.audience')],
        ]);
    }
}
