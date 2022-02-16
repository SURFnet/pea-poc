<?php

declare(strict_types=1);

namespace Tests\Feature\Our\Tool;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Institute;
use App\Models\Tool;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexFilterTest extends TestCase
{
    /** @test */
    public function it_can_filter_tools_by_feature(): void
    {
        $feature = Feature::factory()->create();

        $tool = Tool::factory()->published(true)->create();
        $tool->features()->attach($feature);

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Tool::factory()
            ->published(true)
            ->create()
            ->features()->attach(Feature::factory()->create());

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['filter' => ['features' => [$feature->id]]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_can_filter_tools_by_category(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $institute = $this->informationManager->institute;

        $category = Category::factory()->for($institute)->create();

        $category->tools()->attach($tool);
        $institute->tools()->attach($tool, ['published_at' => now()]);

        $unmatchedTool = Tool::factory()->published(true)->create();
        $institute->tools()->attach($unmatchedTool, ['published_at' => now()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['filter' => ['categories' => [$category->id]]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_can_filter_tools_by_category_and_feature(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $institute = $this->informationManager->institute;
        $institute->tools()->attach($tool, ['published_at' => now()]);

        $category = Category::factory()->for($institute)->create();
        $category->tools()->attach($tool);

        $feature = Feature::factory()->create();
        $tool->features()->attach($feature);

        $unmatchedTool = Tool::factory()->published(true)->create();
        $institute->tools()->attach($unmatchedTool, ['published_at' => now()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['filter' => [
                'features'   => [$feature->id],
                'categories' => [$category->id],
            ]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_does_not_show_filtered_tools_for_other_institutes(): void
    {
        $feature = Feature::factory()->create();
        $institute = Institute::factory()->create();

        $tool = Tool::factory()->published(true)->create();
        $tool->features()->attach($feature);

        $institute->tools()->attach($tool, ['published_at' => now()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['filter' => ['features' => [$feature->id]]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 0)
            );
    }
}
