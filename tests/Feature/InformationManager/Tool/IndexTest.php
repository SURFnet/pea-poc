<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Models\Category;
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
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $tool->name)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function unpublished_tools_are_listed_as_such(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool, ['published_at' => null]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $tool->name)
                            ->where('institute.status_display', trans('institute.tool.statuses.unpublished'))
                            ->etc()
                    )
            );
    }

    /** @test */
    public function only_our_tools_are_listed(): void
    {
        Tool::factory()->published()->create();

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
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
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.tool.index'));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_teacher(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->get(route('information-manager.tool.index'));
    }

    /** @test */
    public function tools_can_be_filtered_by_name(): void
    {
        $otherTool = Tool::factory()->published()->create(['name' => 'irrelevant']);
        $this->informationManager->institute->tools()->attach($otherTool);

        $matchedTool = Tool::factory()->published()->create(['name' => 'matched']);
        $this->informationManager->institute->tools()->attach($matchedTool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index', ['filter' => ['name' => 'match']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->has('tools.data', 1)
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $matchedTool->name)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function tools_can_be_filtered_by_category(): void
    {
        $category = Category::factory()->for($this->admin->institute)->create();

        $otherTool = Tool::factory()->published()->create(['name' => 'irrelevant']);
        $this->informationManager->institute->tools()->attach($otherTool);

        $matchedTool = Tool::factory()->published()->create(['name' => 'matched']);
        $this->informationManager->institute->tools()->attach($matchedTool);
        $category->tools()->attach($matchedTool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index', ['filter' => ['category' => $category->id]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->has('tools.data', 1)
                    ->has(
                        'tools.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $matchedTool->name)
                            ->etc()
                    )
            );
    }
}
