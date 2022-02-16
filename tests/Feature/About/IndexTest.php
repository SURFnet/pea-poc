<?php

declare(strict_types=1);

namespace Tests\Feature\About;

use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited_as_admin(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('about.index'))

            ->assertInertia(
                fn (Assert $page) => $page->component('about/Index')
            );
    }

    /** @test */
    public function the_page_can_be_visited_as_a_content_manager(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->get(route('about.index'))

            ->assertInertia(
                fn (Assert $page) => $page->component('about/Index')
            );
    }

    /** @test */
    public function the_page_can_be_visited_as_an_information_manager(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('about.index'))

            ->assertInertia(
                fn (Assert $page) => $page->component('about/Index')
            );
    }

    /** @test */
    public function the_page_can_be_visited_as_a_teacher(): void
    {
        $this
            ->actingAs($this->teacher)
            ->get(route('about.index'))

            ->assertInertia(
                fn (Assert $page) => $page->component('about/Index')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('about.index'));
    }
}
