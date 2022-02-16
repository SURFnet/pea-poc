<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Models\Tool;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('tool.name', $tool->name)
                    ->where('tool.description_short', $tool->description_short)
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
