<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function content_pages_are_listed(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-page.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-page/Index')
                    ->has(
                        'contentPages.data.0',
                        fn (Assert $page) => $page
                            ->where('title_en', $contentPage->title_en)
                            ->where('body_en', $contentPage->body_en)
                            ->where('slug', $contentPage->slug)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-page.index'));
    }

    /** @test */
    public function content_pages_can_be_filtered_by_name(): void
    {
        $contentPage = ContentPage::factory()->create(['title_en' => 'match_this_title']);
        ContentPage::factory()->create(['title_en' => 'foo_bar']);

        $this
            ->actingAs($this->admin)
            ->get(route('content-page.index', ['filter' => ['title_en' => 'match_this_title']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-page/Index')
                    ->has('contentPages.data', 1)
                    ->has(
                        'contentPages.data.0',
                        fn (Assert $page) => $page
                            ->where('title_en', 'match_this_title')
                            ->etc()
                    )
            );
    }
}
