<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Models\Feature;
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
        $tool = Tool::factory()->published(true)->create([$field => '::search::']);

        Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index', ['search' => '::search::']))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    /** @test */
    public function it_can_search_within_filters(): void
    {
        $feature = Feature::factory()->create();

        $tool = Tool::factory()->published(true)->create(['name' => '::search::']);
        $tool->features()->attach($feature);

        $unmatchedTool = Tool::factory()->published(true)->create();
        $unmatchedTool->features()->attach($feature);

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index', [
                'search' => '::search::',
                'filter' => ['features' => [$feature->id]],
            ]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }

    public function searchFields(): array
    {
        return array_map(fn ($field) => [$field], Tool::$searchFields);
    }
}
