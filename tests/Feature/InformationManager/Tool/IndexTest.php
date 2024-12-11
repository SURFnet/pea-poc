<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Enums\InstituteTool\Sort;
use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
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
                            ->where('institute.status_display', Status::getTranslation(Status::UNPUBLISHED))
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
                    ->where('tools.data.0.id', $tool->id)
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
                    ->where('tools.data.0.id', $matchedTool->id)
            );
    }

    /** @test */
    public function tools_can_be_filtered_by_category(): void
    {
        $institute = $this->informationManager->institute;

        $otherTool = Tool::factory()->published()->create(['name' => 'irrelevant']);
        $institute->tools()->attach($otherTool);

        /** @var Tool $matchedTool */
        $matchedTool = Tool::factory()->published()->create(['name' => 'matched']);
        $institute->tools()->attach($matchedTool);

        $instituteTool = InstituteTool::forInstitute($institute)->forTool($matchedTool)->first();

        $categoryTag = Tag::factory()
            ->for($institute)
            ->create([
                'type' => TagTypes::CATEGORIES,
            ]);

        $instituteTool->syncTagsWithType([$categoryTag], TagTypes::CATEGORIES);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index', ['filter' => ['category' => $categoryTag->id]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->has('tools.data', 1)
                    ->where('tools.data.0.id', $matchedTool->id)
            );
    }

    /** @test */
    public function description_is_translated(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published()->create([
            'description_short_nl' => 'description-short-nl',
        ]);
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
                            ->where('description_short', $tool->description_short_nl)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function tools_can_be_sorted_by_institute_tools_updated_at_asc(): void
    {
        $institute = $this->informationManager->institute;

        $firstTool = Tool::factory()->published()->create();
        $institute->tools()->attach($firstTool, ['updated_at' => now()->subYear()]);

        $secondTool = Tool::factory()->published()->create();
        $institute->tools()->attach($secondTool, ['updated_at' => now()->subYear()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index', ['sort' => Sort::UPDATED_AT_ASC]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->where('tools.data.0.id', $firstTool->id)
            );
    }

    /** @test */
    public function tools_can_be_sorted_by_institute_tools_updated_at_desc(): void
    {
        $institute = $this->informationManager->institute;

        $firstTool = Tool::factory()->published()->create();
        $institute->tools()->attach($firstTool, ['updated_at' => now()->subYear()]);

        $secondTool = Tool::factory()->published()->create();
        $institute->tools()->attach($secondTool, ['updated_at' => now()]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.index', ['sort' => Sort::UPDATED_AT_DESC]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Index')
                    ->where('tools.data.0.id', $secondTool->id)
            );
    }
}
