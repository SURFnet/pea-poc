<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Edit')
                    ->where('tool.name', $tool->name)
            );
    }

    /** @test */
    public function the_page_can_only_be_visited_for_institute_owned_tools(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_only_be_visited_for_published_tools(): void
    {
        $tool = Tool::factory()->published(false)->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_teacher(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.tool.edit', $tool));
    }
}
