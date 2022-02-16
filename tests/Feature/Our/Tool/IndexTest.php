<?php

declare(strict_types=1);

namespace Tests\Feature\Our\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Feature;
use App\Models\Institute;
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
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
            );
    }

    /** @test */
    public function it_contains_tools_that_are_published_for_own_institute(): void
    {
        $tools = Tool::factory()->published(true)->count(3)->create();

        $this->informationManager->institute->tools()->attach($tools, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 3)
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
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->where('tools.0.rating', 3)
            );
    }

    /** @test */
    public function it_does_not_contain_tools_for_other_institutes(): void
    {
        $tool = Tool::factory()->published(true)->create();
        $institue = Institute::factory()->create();

        $institue->tools()->attach($tool, ['published_at' => now()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 0)
            );
    }

    /** @test */
    public function tools_are_ordered_by_name(): void
    {
        $tools = Tool::factory()->published(true)->count(3)
            ->state(new Sequence(
                ['name' => 'ccc'],
                ['name' => 'aaa'],
                ['name' => 'bbb'],
            ))->create();

        $this->informationManager->institute->tools()->attach($tools, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->where('tools.0.name', 'aaa')
                    ->where('tools.1.name', 'bbb')
                    ->where('tools.2.name', 'ccc')
            );
    }

    /** @test */
    public function tools_are_ordered_by_status_and_name(): void
    {
        $presets = [
            ['name' => 'bbb', 'status' => Status::FREE_TO_USE],
            ['name' => 'bba', 'status' => Status::FREE_TO_USE],
            ['name' => 'bbc', 'status' => Status::SUPPORTED],
            ['name' => 'aaa', 'status' => Status::NOT_RECOMMENDED],
            ['name' => 'aab', 'status' => Status::NOT_RECOMMENDED],
            ['name' => 'fff', 'status' => Status::RECOMMENDED],
        ];

        foreach ($presets as $preset) {
            $this->teacher->institute->tools()->attach(
                Tool::factory()->published(true)->create(['name' => $preset['name']]),
                [
                    'status'       => $preset['status'],
                    'published_at' => now(),
                ]
            );
        }

        $this
            ->actingAs($this->teacher)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->where('tools.0.name', 'fff')
                    ->where('tools.1.name', 'bbc')
                    ->where('tools.2.name', 'bba')
                    ->where('tools.3.name', 'bbb')
                    ->where('tools.4.name', 'aaa')
                    ->where('tools.5.name', 'aab')
            );
    }

    /** @test */
    public function it_does_not_contain_unpublished_tools(): void
    {
        $tools = Tool::factory()->published(false)->create();

        $this->informationManager->institute->tools()->attach($tools);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 0)
            );
    }

    /** @test */
    public function the_page_has_a_sidebar_with_features(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('sidebar.features', Feature::count())
            );
    }

    /** @test */
    public function the_page_has_a_sidebar_with_categories_for_the_institute(): void
    {
        Category::factory()->for($this->informationManager->institute)->count(3)->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('sidebar.categories', 3)
            );
    }

    /** @test */
    public function the_sidebar_does_not_include_categories_for_other_institutes(): void
    {
        Category::factory()->for(Institute::factory()->create())->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('sidebar.categories', 0)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('our.tool.index'));
    }
}
