<?php

declare(strict_types=1);

namespace Tests\Feature\Our\Tool;

use App\Models\Feature;
use App\Models\Institute;
use App\Models\Tool;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexSearchTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider searchFields
     */
    public function it_can_search_for_tools(string $field): void
    {
        $institute = $this->informationManager->institute;

        $tool = Tool::factory()->published(true)->create([$field => '::search::']);
        $institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $unmatchedTool = Tool::factory()->published(true)->create();
        $institute->tools()->attach($unmatchedTool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['search' => '::search::']))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_can_search_within_filters(): void
    {
        $institute = $this->informationManager->institute;
        $feature = Feature::factory()->create();

        $tool = Tool::factory()->published(true)->create(['name' => '::search::']);
        $tool->features()->attach($feature);

        $institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $unmatchedTool = Tool::factory()->published(true)->create();
        $unmatchedTool->features()->attach($feature);
        $institute->tools()->attach($unmatchedTool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', [
                'search' => '::search::',
                'filter' => ['features' => [$feature->id]],
            ]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_does_not_show_tools_for_other_institutes(): void
    {
        $institute = Institute::factory()->create();

        $tool = Tool::factory()->published(true)->create(['name' => '::search::']);
        $institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('our.tool.index', ['search' => '::search::']))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Index')
                    ->has('tools', 0)
            );
    }

    public function searchFields(): array
    {
        return array_map(fn ($field) => [$field], Tool::$searchFields);
    }
}
