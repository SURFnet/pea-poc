<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('content-page.index'))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-page/Index')
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
    public function the_page_can_not_be_visited_by_an_information_manager(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('content-page.index'))

            ->assertForbidden();
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_content_manager(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->get(route('content-page.index'))

            ->assertForbidden();
    }
}
