<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

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
            ->get(route('content-manager.tool.create'))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Create')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tool.create'));
    }
}
