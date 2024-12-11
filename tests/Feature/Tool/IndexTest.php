<?php

declare(strict_types=1);

namespace Tests\Feature\Tool;

use App\Enums\Auth\Role;
use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use App\Models\Experience;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Helpers\ToolHelper;
use Tests\TestCase;

// Note: More in-depth tests are in Tests\Unit\Models\Tool\OverviewTest
class IndexTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
            );
    }

    /** @test */
    public function it_contains_tools_that_are_published(): void
    {
        $tools = Tool::factory()->published(true)->count(3)->create();

        $this->informationManager->institute->tools()->attach($tools, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('tools.data', 3)
            );
    }

    /** @test */
    public function it_knows_the_total_experience_amount_for_a_tool(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        Experience::factory(3)->for($tool)->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->where('tools.data.0.total_experiences', 3)
            );
    }

    /** @test */
    public function it_returns_null_when_there_is_no_initial_filter(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('initialFilter', null)
            );
    }

    /** @test */
    public function tools_are_ordered_by_english_name(): void
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
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->where('tools.data.0.name', 'aaa')
                    ->where('tools.data.1.name', 'bbb')
                    ->where('tools.data.2.name', 'ccc')
            );
    }

    /** @test */
    public function tools_are_ordered_by_status_and_name(): void
    {
        $presets = [
            ['name' => 'bbb', 'status' => Status::ALLOWED],
            ['name' => 'bba', 'status' => Status::ALLOWED],
            ['name' => 'bbc', 'status' => Status::ALLOWED_UNDER_CONDITIONS],
            ['name' => 'aaa', 'status' => Status::DISALLOWED],
            ['name' => 'aab', 'status' => Status::ALLOWED_UNDER_CONDITIONS],
            ['name' => 'fff', 'status' => Status::ALLOWED],
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
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->where('tools.data.0.name', 'bba')
                    ->where('tools.data.1.name', 'bbb')
                    ->where('tools.data.2.name', 'fff')
                    ->where('tools.data.3.name', 'aab')
                    ->where('tools.data.4.name', 'bbc')
                    ->where('tools.data.5.name', 'aaa')
            );
    }

    /** @test */
    public function it_does_not_contain_unpublished_tools(): void
    {
        $tools = Tool::factory()->published(false)->create();

        $this->informationManager->institute->tools()->attach($tools);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('tools.data', 0)
            );
    }

    /** @test */
    public function the_page_has_a_sidebar_with_tags(): void
    {
        $institute = $this->informationManager->institute;
        Tag::factory()->count(3)->for($institute)->create(['type' => TagTypes::CERTIFICATIONS]);
        Tag::factory()->count(2)->create(['type' => TagTypes::CATEGORIES, 'institute_id' => null]);

        // For wrong institute
        Tag::factory()->count(3)->for(Institute::factory()->create())->create(['type' => TagTypes::CERTIFICATIONS]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('sidebar.tags.certifications', 3)
                    ->count('sidebar.tags.categories', 2)
            );
    }

    /** @test */
    public function the_page_sidebar_contains_only_tag_types_for_user_with_only_teacher_role(): void
    {
        $user = $this->teacher;

        $this->createTagsForAllTypes($user->institute);

        $this
            ->actingAs($user)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('sidebar.tags', count(TagTypes::forTeacher()))
            );
    }

    /** @test */
    public function the_page_sidebar_contains_all_tag_types_for_user_with_teacher_and_other_role(): void
    {
        $user = $this->teacher;
        $user->roles = [Role::TEACHER, Role::CONTENT_MANAGER];
        $user->save();

        $this->createTagsForAllTypes($user->institute);

        $this
            ->actingAs($user)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('sidebar.tags', count(TagTypes::toArray()))
            );
    }

    /** @test */
    public function the_page_sidebar_contains_all_tag_types_for_user_with_any_role_other_than_teacher(): void
    {
        $user = $this->contentManager;

        $this->createTagsForAllTypes($user->institute);

        $this
            ->actingAs($user)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('sidebar.tags', count(TagTypes::toArray()))
            );
    }

    /** @test */
    public function the_page_sidebar_tags_are_correctly_ordered_for_user_with_only_teacher_role(): void
    {
        $user = $this->teacher;

        $this->createTagsForAllTypes($user->institute);

        $response = $this
            ->actingAs($user)
            ->get(route('tool.index'));

        $sidebarTags = array_keys($response->viewData('page')['props']['sidebar']['tags']);

        $this->assertEquals($sidebarTags, TagTypes::forTeacher());
    }

    /** @test */
    public function the_page_sidebar_tags_are_correctly_ordered_for_user_with_teacher_and_other_role(): void
    {
        $user = $this->teacher;
        $user->roles = [Role::TEACHER, Role::CONTENT_MANAGER];
        $user->save();

        $this->createTagsForAllTypes($user->institute);

        $response = $this
            ->actingAs($user)
            ->get(route('tool.index'));

        $sidebarTags = array_keys($response->viewData('page')['props']['sidebar']['tags']);

        $this->assertEquals($sidebarTags, array_values(TagTypes::toArray()));
    }

    /** @test */
    public function the_page_sidebar_tags_are_correctly_ordered_for_user_with_any_role_other_than_teacher(): void
    {
        $user = $this->contentManager;

        $this->createTagsForAllTypes($user->institute);

        $response = $this
            ->actingAs($user)
            ->get(route('tool.index'));

        $sidebarTags = array_keys($response->viewData('page')['props']['sidebar']['tags']);

        $this->assertEquals($sidebarTags, array_values(TagTypes::toArray()));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('tool.index'));
    }

    /** @test */
    public function description_is_translated(): void
    {
        $this->app->setLocale('nl');

        $tool = Tool::factory()->published(true)->create([
            'description_short_nl' => 'description-short-nl',
        ]);

        $this->informationManager->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->where('tools.data.0.description_short', $tool->description_short_nl)
            );
    }

    /** @test */
    public function it_knows_the_total_count_even_when_using_pagination(): void
    {
        $tools = Tool::factory()->published(true)->count(16)->create();

        $this->informationManager->institute->tools()->attach($tools, [
            'published_at' => now(),
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index', ['page' => 2]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('tools.data', 6)
                    ->where('totalToolCount', 16)
            );
    }

    /** @test */
    public function tools_can_be_searched_and_filtered(): void
    {
        $institute = Institute::factory()->create();
        $tag = Tag::factory()->create();
        $otherTags = Tag::factory()->count(2)->create();

        $matchingTool = ToolHelper::create($institute, true, ['name' => 'Google searchterm assistant 1'], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], [$tag]);

        // Non-matching tools
        ToolHelper::create($institute, true, ['name' => 'Bladiebla'], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], [$tag]);
        ToolHelper::create($institute, true, ['name' => 'Google searchterm assistant 2'], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], []);
        ToolHelper::create($institute, true, ['name' => 'Google searchterm assistant 3'], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], $otherTags->all());

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index', [
                'search' => 'searchterm',
                'tags'   => $tag->type . ':' . $tag->slug,
            ]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('tools.data', 1)
                    ->where('tools.data.0.id', $matchingTool->id)
            );
    }

    /** @test */
    public function multiple_types_of_tags_can_be_filtered_at_once(): void
    {
        $institute = Institute::factory()->create();
        $tags = [
            Tag::factory()->create(['institute_id' => null, 'type' => TagTypes::CERTIFICATIONS, 'name' => 'slug-1']),
            Tag::factory()->create(['institute_id' => null, 'type' => TagTypes::CERTIFICATIONS, 'name' => 'slug-2']),
            Tag::factory()->create(['institute_id' => null, 'type' => TagTypes::COMPLEXITY, 'name' => 'slug-3']),
            Tag::factory()->create(['institute_id' => null, 'type' => TagTypes::DEVICES, 'name' => 'slug-4']),
        ];

        $matchingTool = ToolHelper::create($institute, true, [], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], $tags);

        // Non-matching tools
        ToolHelper::create($institute, true, [], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], [$tags[0], $tags[1], $tags[2]]);

        ToolHelper::create($institute, true, [], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], [$tags[1], $tags[2]]);

        ToolHelper::create($institute, true, [], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], []);

        $this
            ->actingAs($this->informationManager)
            ->get(route('tool.index', [
                'tags' => implode('/', [
                    TagTypes::CERTIFICATIONS . ':slug-1,slug-2',
                    TagTypes::COMPLEXITY . ':slug-3',
                    TagTypes::DEVICES . ':slug-4',
                ]),
            ]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('tool/Index')
                    ->count('tools.data', 1)
                    ->where('tools.data.0.id', $matchingTool->id)
            );
    }

    /**
     * @test
     *
     * @dataProvider instituteToolsPublishedProvider
     */
    public function institute_tools_are_correctly_shown_when_filtering_by_institute_category_tag(
        bool $isPublished,
        bool $shouldBeVisible
    ): void {
        $institute = Institute::factory()->create();

        $user = $this->informationManager;
        $user->institute()->associate($institute);

        $instituteCategoryTag = Tag::factory()
            ->for($institute)
            ->create([
                'type' => TagTypes::CATEGORIES,
                'name' => 'Institute specific category',
            ]);

        $tool = Tool::factory()
            ->published()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($institute)
            ->for($tool)
            ->published($isPublished)
            ->create();

        $instituteTool->syncTags([$instituteCategoryTag]);

        $this
            ->actingAs($user)
            ->get(route('tool.index', [
                'search' => '',
                'tags'   => $instituteCategoryTag->type . ':' . $instituteCategoryTag->slug,
            ]))

            ->assertInertia(function (Assert $page) use ($shouldBeVisible, $tool): void {
                $page->component('tool/Index');

                if ($shouldBeVisible) {
                    $page->count('tools.data', 1);
                    $page->where('tools.data.0.id', $tool->id);
                } else {
                    $page->count('tools.data', 0);
                }
            });
    }

    public function instituteToolsPublishedProvider(): array
    {
        return [
            [false, false],
            [true, true],
        ];
    }

    private function createTagsForAllTypes(Institute $institute): void
    {
        foreach (TagTypes::toArray() as $tagType) {
            Tag::factory()
                ->for($institute)
                ->create(['type' => $tagType]);
        }
    }
}
