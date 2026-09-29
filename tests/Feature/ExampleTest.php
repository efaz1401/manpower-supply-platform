<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * `/` is a locale hand-off, not a content page: it 302s to the default
     * locale so that /ar and /en stay the only canonical, indexable homepages.
     */
    public function test_the_application_redirects_the_root_to_the_default_locale(): void
    {
        $this->get('/')
            ->assertStatus(302)
            ->assertRedirect('/ar');
    }
}
