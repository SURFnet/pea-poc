<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-page.edit', $contentPage))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-page/Edit')
                    ->where('contentPage.body_en', $contentPage->body_en)
                    ->where('contentPage.title_en', $contentPage->title_en)
                    ->where('contentPage.slug', $contentPage->slug)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $contentPage = ContentPage::factory()->create();

        $this
            ->get(route('content-page.edit', $contentPage));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_an_information_manager(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('content-page.edit', $contentPage))

            ->assertForbidden();
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_content_manager(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->contentManager)
            ->get(route('content-page.edit', $contentPage))

            ->assertForbidden();
    }
}
