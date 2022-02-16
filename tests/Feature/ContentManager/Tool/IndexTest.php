<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Models\Feature;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function tools_are_listed(): void
    {
        $tool = Tool::factory()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Index')
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $tool->name)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function the_page_can_be_visited_by_an_admin(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.index'))

            ->assertOk();
    }

    /** @test */
    public function the_page_can_be_visited_by_a_content_manager(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->get(route('content-manager.tool.index'))

            ->assertOk();
    }

    /** @test */
    public function the_page_can_not_be_visited_by_an_information_manager(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('content-manager.tool.index'));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_teacher(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->get(route('content-manager.tool.index'));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tool.index'));
    }

    /** @test */
    public function tools_can_be_filtered_by_name(): void
    {
        Tool::factory()->create(['name' => 'irrelevant']);
        $tool = Tool::factory()->create(['name' => 'matched']);

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.index', ['filter' => ['name' => 'match']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Index')
                    ->has('tools.data', 1)
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $tool->name)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function tools_can_be_filtered_by_feature(): void
    {
        $feature = Feature::factory()->create(['name' => 'awesome']);

        Tool::factory()->create();

        $tool = Tool::factory()->create(['name' => 'feature-tool']);
        $tool->features()->attach($feature);

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.index', ['filter' => ['feature' => 'awesome']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Index')
                    ->has('tools.data', 1)
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $tool->name)
                            ->etc()
                    )
            );
    }
}
