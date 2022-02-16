<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Models\Experience;
use App\Models\Feature;
use App\Models\Tool;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
            );
    }

    /** @test */
    public function it_contains_tools(): void
    {
        Tool::factory()->published(true)->count(3)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 3)
            );
    }

    /** @test */
    public function it_does_not_contain_tools_that_are_published_for_our_institute(): void
    {
        Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach(Tool::factory()->published(true)->create(), [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 1)
            );
    }

    /** @test */
    public function it_does_contain_tools_that_are_not_published_for_our_institute(): void
    {
        Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach(Tool::factory()->published(true)->create(), [
            'published_at' => null,
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 2)
            );
    }

    /** @test */
    public function it_knows_the_rating_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        Experience::factory()->for($tool)->create(['rating' => 3]);

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->where('tools.0.rating', 3)
            );
    }

    /** @test */
    public function tools_are_ordered_by_name(): void
    {
        Tool::factory()->published(true)->count(3)
            ->state(new Sequence(
                ['name' => 'ccc'],
                ['name' => 'aaa'],
                ['name' => 'bbb'],
            ))->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->where('tools.0.name', 'aaa')
                    ->where('tools.1.name', 'bbb')
                    ->where('tools.2.name', 'ccc')
            );
    }

    /** @test */
    public function it_does_not_contain_unpublished_tools(): void
    {
        Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 0)
            );
    }

    /** @test */
    public function the_page_has_a_sidebar_with_features(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('sidebar.features', Feature::count())
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('other.tool.index'));
    }
}
