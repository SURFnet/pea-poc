<?php

declare(strict_types=1);

namespace Tests\Feature\Our\Tool;

use App\Models\Experience;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_for_tools_that_are_not_published_for_the_institute(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => null,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_for_tools_the_institute_does_not_own(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool));
    }

    /** @test */
    public function it_contains_the_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('tool.id', $tool->id)
            );
    }

    /** @test */
    public function it_knows_the_rating_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Experience::factory()->for($tool)->create(['rating' => 3]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('tool.rating', 3)
            );
    }

    /** @test */
    public function it_contains_the_back_url_to_our_tools_index(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('backUrl', route('our.tool.index'))
            );
    }

    /** @test */
    public function it_contains_the_experiences_in_latest_order(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Experience::factory()->sequence(
            [
                'title'      => '::experience-1::',
                'created_at' => Carbon::now()->subWeek(),
            ],
            [
                'title'      => '::experience-2::',
                'created_at' => Carbon::now()->yesterday(),
            ],
            [
                'title'      => '::experience-3::',
                'created_at' => Carbon::now(),
            ],
        )->count(3)->for($tool)->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('our/tool/Show')
                    ->where('experiences.0.title', '::experience-3::')
                    ->where('experiences.1.title', '::experience-2::')
                    ->where('experiences.2.title', '::experience-1::')
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('our.tool.show', $tool));
    }
}
