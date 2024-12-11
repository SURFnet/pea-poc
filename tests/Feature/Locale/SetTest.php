<?php

declare(strict_types=1);

namespace Tests\Feature\Locale;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Way2Translate\Models\Language;
use Tests\TestCase;

class SetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()->activated()->create(['locale' => 'nl']);
        Language::factory()->activated()->create(['locale' => 'en']);

        Cache::flush();
        Config::set('way2translate.locales', ['en' => [], 'nl' => []]);
    }

    /** @test */
    public function the_locale_is_saved_to_the_user_and_the_user_is_redirected(): void
    {
        $this->admin->language = 'en';
        $this->admin->save();

        $this
            ->actingAs($this->admin)
            ->from(route('about.index'))
            ->get(route('locale.set', 'nl'))

            ->assertRedirect(LaravelLocalization::localizeUrl(route('about.index'), 'nl'));

        $this->assertEquals('nl', $this->admin->fresh()->language);
    }
}
