<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tag;

use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->get(route('content-manager.tag.create'))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tag/Create')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tag.create'));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_information_manager(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('content-manager.tag.create'))
            ->assertForbidden();
    }
}
