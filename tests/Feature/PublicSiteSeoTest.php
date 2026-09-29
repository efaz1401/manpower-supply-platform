<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Guards the SEO contract of the public marketing site.
 *
 * These assertions exist because every one of them is a silent failure mode:
 * a broken canonical, a missing hreflang or a leaked translation key does not
 * throw, it just quietly costs rankings.
 */
class PublicSiteSeoTest extends TestCase
{
    /** @return array<int, string> */
    protected function allPublicPaths(string $locale): array
    {
        $paths = [
            "/$locale",
            "/$locale/services",
            "/$locale/ajeer",
            "/$locale/about",
            "/$locale/faq",
            "/$locale/contact",
        ];

        foreach (array_keys(config('company.services')) as $service) {
            $paths[] = "/$locale/services/$service";
        }

        foreach (array_keys(config('company.cities')) as $city) {
            $paths[] = "/$locale/manpower-supply/$city";
        }

        return $paths;
    }

    public function test_root_hands_off_to_the_default_locale(): void
    {
        $this->get('/')->assertRedirect('/ar');
    }

    public function test_every_public_page_renders_in_both_locales(): void
    {
        foreach (['ar', 'en'] as $locale) {
            foreach ($this->allPublicPaths($locale) as $path) {
                $this->get($path)->assertOk();
            }
        }
    }

    /**
     * A missing lang key renders as the literal dotted key. If that reaches
     * production it shows up verbatim in Google's snippet.
     */
    public function test_no_page_leaks_an_untranslated_key(): void
    {
        foreach (['ar', 'en'] as $locale) {
            foreach ($this->allPublicPaths($locale) as $path) {
                $html = $this->get($path)->getContent();

                $this->assertDoesNotMatchRegularExpression(
                    '/marketing\.[a-z0-9_.\-]+/i',
                    $html,
                    "Untranslated key leaked on $path"
                );
            }
        }
    }

    public function test_pages_are_self_canonical(): void
    {
        foreach (['/ar', '/en/services', '/ar/manpower-supply/al-ahsa'] as $path) {
            $html = $this->get($path)->getContent();

            $this->assertStringContainsString(
                '<link rel="canonical" href="'.config('app.url').$path.'">',
                $html,
                "Canonical is wrong on $path"
            );
        }
    }

    public function test_pages_declare_hreflang_for_every_locale_plus_x_default(): void
    {
        $html = $this->get('/ar/services/cleaning-manpower')->getContent();

        $this->assertStringContainsString('hreflang="ar-SA" href="'.config('app.url').'/ar/services/cleaning-manpower"', $html);
        $this->assertStringContainsString('hreflang="en" href="'.config('app.url').'/en/services/cleaning-manpower"', $html);
        $this->assertStringContainsString('hreflang="x-default" href="'.config('app.url').'/ar/services/cleaning-manpower"', $html);
    }

    public function test_public_pages_are_indexable(): void
    {
        $this->get('/ar')->assertSee('name="robots" content="index, follow', false);
    }

    public function test_thank_you_page_is_not_indexable(): void
    {
        $this->get('/ar/thank-you')->assertSee('name="robots" content="noindex', false);
    }

    public function test_unknown_locale_service_and_city_all_404(): void
    {
        $this->get('/fr')->assertNotFound();
        $this->get('/ar/services/does-not-exist')->assertNotFound();
        $this->get('/ar/manpower-supply/does-not-exist')->assertNotFound();
    }

    public function test_json_ld_is_valid_and_identifies_the_organisation(): void
    {
        $html = $this->get('/ar')->getContent();

        $this->assertMatchesRegularExpression('#<script type="application/ld\+json">(.+?)</script>#s', $html);
        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $m);

        $graph = json_decode($m[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'JSON-LD is not valid JSON');
        $this->assertArrayHasKey('@graph', $graph);

        $types = array_map(fn ($node) => (array) $node['@type'], $graph['@graph']);
        $flat = array_merge(...$types);

        foreach (['Organization', 'EmploymentAgency', 'WebSite', 'WebPage', 'FAQPage'] as $expected) {
            $this->assertContains($expected, $flat, "JSON-LD graph is missing a $expected node");
        }

        $org = collect($graph['@graph'])->firstWhere('@id', config('app.url').'/#organization');

        $this->assertNotNull($org);
        $this->assertSame(config('company.phone'), $org['telephone']);
        $this->assertSame('SA', $org['areaServed']['identifier']);
    }

    public function test_city_pages_carry_geo_coordinates_matching_the_config(): void
    {
        $html = $this->get('/ar/manpower-supply/jubail')->getContent();
        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $m);
        $graph = json_decode($m[1], true);

        $place = collect($graph['@graph'])->firstWhere('@type', 'Place');

        $this->assertSame(config('company.cities.jubail.lat'), $place['geo']['latitude']);
        $this->assertSame(config('company.cities.jubail.lng'), $place['geo']['longitude']);
    }

    public function test_sitemap_lists_every_page_in_every_locale_with_alternates(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());

        $this->assertNotFalse($xml, 'sitemap.xml is not well-formed XML');

        $expected = count($this->allPublicPaths('ar')) * 2;

        $this->assertCount($expected, $xml->url);

        // preserve_keys must be false: every node is named `url`, so keeping
        // keys collapses all 66 of them into one entry.
        $locs = array_map(fn ($url) => (string) $url->loc, iterator_to_array($xml->url, false));

        $this->assertSame($locs, array_unique($locs), 'sitemap contains duplicate URLs');
        $this->assertContains(config('app.url').'/ar/manpower-supply/al-ahsa', $locs);
        $this->assertContains(config('app.url').'/en/ajeer', $locs);

        // thank-you is noindex, so it must never appear in the sitemap.
        $this->assertNotContains(config('app.url').'/ar/thank-you', $locs);
    }

    public function test_llms_txt_is_served_as_plain_text(): void
    {
        $this->get('/llms.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Adel Saad Mater Contracting')
            ->assertSee(config('company.cr_number'));
    }

    public function test_authenticated_app_shell_is_excluded_from_indexing(): void
    {
        $this->get('/login')->assertSee('name="robots" content="noindex, nofollow"', false);
    }
}
