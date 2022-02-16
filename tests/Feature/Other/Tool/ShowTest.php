<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Experience;
use App\Models\Institute;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Show')
            );
    }

    /** @test */
    public function it_contains_the_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('tool.id', $tool->id)
            );
    }

    /** @test */
    public function it_knows_which_institutes_use_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $institutes = [
            ['full_name' => 'Institute 001'],
            ['full_name' => 'Institute 002'],
        ];

        $tool->institutes()->attach(
            Institute::factory()->sequence(...$institutes)->count(2)->create(),
            ['published_at' => now(), 'status' => Status::SUPPORTED]
        );

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                        ->component('other/tool/Show')
                        ->where('tool.id', $tool->id)
                        ->has('institutes', 2)
                        ->where('institutes.0.full_name', $institutes[0]['full_name'])
                        ->where('institutes.1.full_name', $institutes[1]['full_name'])
            );
    }

    /** @test */
    public function it_knows_the_rating_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        Experience::factory()->for($tool)->create(['rating' => 3]);

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('tool.rating', 3)
            );
    }

    /** @test */
    public function it_contains_the_back_url_to_other_tools_index(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
                    ->where('backUrl', route('other.tool.index'))
            );
    }

    /** @test */
    public function it_contains_the_experiences_in_latest_order(): void
    {
        $tool = Tool::factory()->published(true)->create();

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
            ->actingAs($this->admin)
            ->get(route('other.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                ->component('other/tool/Show')
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
            ->get(route('other.tool.show', $tool));
    }
}
