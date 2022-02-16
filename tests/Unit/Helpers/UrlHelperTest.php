<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\Url;
use Tests\TestCase;

class UrlHelperTest extends TestCase
{
    /** @test */
    public function it_can_get_the_subdomain_from_the_url_root(): void
    {
        // Set config values that normally read from the .env file, in order to test easily.
        config(['constants.general.protocol' => 'http://']);
        config(['constants.general.domain' => 'website.com']);

        $this->assertEquals('shop', Url::getSubdomain('http://shop.website.com'));
    }
}
