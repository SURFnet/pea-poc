<?php

declare(strict_types=1);

namespace Feature\InformationManager\HomepageInformation;

use App\Models\Institute;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route(
                'information-manager.homepage-information.edit'
            ))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/homepage-information/Edit')
                    ->where('institute.homepage_title_en', $this->informationManager->institute->homepage_title_en)
                    ->where('institute.homepage_body_en', $this->informationManager->institute->homepage_body_en)
                    ->where('institute.homepage_title_nl', $this->informationManager->institute->homepage_title_nl)
                    ->where('institute.homepage_body_nl', $this->informationManager->institute->homepage_body_nl)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_as_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $institute = Institute::factory()->create();

        $this
            ->get(route(
                'information-manager.homepage-information.edit',
            ))
            ->assertForbidden();
    }
}
