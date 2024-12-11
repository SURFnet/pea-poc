<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function an_information_manager_can_visit_the_page(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('content-page.show', $contentPage))

            ->assertOk();
    }

    /** @test */
    public function a_content_manager_can_visit_the_page(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->contentManager)
            ->get(route('content-page.show', $contentPage))

            ->assertOk();
    }

    /** @test */
    public function a_teacher_can_visit_the_page(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->teacher)
            ->get(route('content-page.show', $contentPage))
            ->assertOk();
    }
}
