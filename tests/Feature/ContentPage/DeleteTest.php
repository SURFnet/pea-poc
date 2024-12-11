<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_be_deleted(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->admin)
            ->delete(route('content-page.destroy', $contentPage))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-page.index'));

        $this->assertDatabaseMissing('content_pages', [
            'id' => $contentPage->id,
        ]);
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $contentPage = ContentPage::factory()->create();

        $this
            ->delete(route('content-page.destroy', $contentPage));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_an_information_manager(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->informationManager)
            ->delete(route('content-page.destroy', $contentPage))

            ->assertForbidden();
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_content_manager(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->contentManager)
            ->delete(route('content-page.destroy', $contentPage))

            ->assertForbidden();
    }
}
