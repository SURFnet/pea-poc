<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Models\Feature;
use App\Models\Tool;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexFilterTest extends TestCase
{
    /** @test */
    public function it_can_filter_tools_by_features(): void
    {
        $feature = Feature::factory()->create();

        $tool = Tool::factory()->published(true)->create();
        $tool->features()->attach($feature);

        Tool::factory()
            ->published(true)
            ->create()
            ->features()->attach(Feature::factory()->create());

        $this
            ->actingAs($this->admin)
            ->get(route('other.tool.index', ['filter' => ['features' => [$feature->id]]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('other/tool/Index')
                    ->has('tools', 1)
                    ->where('tools.0.name', $tool->name)
            );
    }
}
