<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Tests\Helpers\ToolHelper;
use Tests\TestCase;

class OverviewTest extends TestCase
{
    /** @test */
    public function it_gets_tools_for_the_institute(): void
    {
        $institute = Institute::factory()->create();

        $tool = ToolHelper::create($institute, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($tool->id, $result[0]->id);
        $this->assertEquals($instituteTool->id, $result[0]->institute_tool_id);
        $this->assertEquals($instituteTool->status, $result[0]->status_institute);
    }

    /** @test */
    public function it_gets_tools_without_institute(): void
    {
        $institute = Institute::factory()->create();

        $tool = ToolHelper::create();

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($tool->id, $result[0]->id);
        $this->assertEquals(null, $result[0]->institute_tool_id);
        $this->assertEquals(null, $result[0]->status_institute);
    }

    /** @test */
    public function it_gets_tools_that_are_only_attached_to_a_different_institute_as_generic_tools(): void
    {
        $institute = Institute::factory()->create();

        $tool = ToolHelper::create(Institute::factory()->create(), attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($tool->id, $result[0]->id);
        $this->assertEquals(null, $result[0]->institute_tool_id);
        $this->assertEquals(null, $result[0]->status_institute);
    }

    /** @test */
    public function tools_that_are_not_published_within_the_institute_are_shown_as_generic_tools(): void
    {
        $institute = Institute::factory()->create();

        $tool = ToolHelper::create($institute, attributesInstitute: [
            'published_at' => null,
            'status'       => Status::ALLOWED,
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($tool->id, $result[0]->id);
        $this->assertEquals(null, $result[0]->institute_tool_id);
        $this->assertEquals(null, $result[0]->status_institute);
    }

    /** @test */
    public function tools_with_institute_get_put_at_the_top(): void
    {
        $institute = Institute::factory()->create();

        $toolWithoutInstitute = ToolHelper::create();
        $toolWithInstitute = ToolHelper::create($institute, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(2, $result);
        $this->assertEquals($toolWithInstitute->id, $result[0]->id);
        $this->assertEquals($toolWithoutInstitute->id, $result[1]->id);
    }

    /** @test */
    public function tools_with_institute_are_sorted_by_status(): void
    {
        $institute = Institute::factory()->create();

        $toolWithoutInstitute = ToolHelper::create();
        $tools = [
            ToolHelper::create($institute, attributesInstitute: [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED_UNDER_CONDITIONS,
            ]),
            ToolHelper::create($institute, attributesInstitute: [
                'published_at' => Carbon::now(),
                'status'       => Status::DISALLOWED,
            ]),
            ToolHelper::create($institute, attributesInstitute: [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ]),
        ];

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(4, $result);
        $this->assertEquals($tools[2]->id, $result[0]->id);
        $this->assertEquals($tools[0]->id, $result[1]->id);
        $this->assertEquals($tools[1]->id, $result[2]->id);
        $this->assertEquals($toolWithoutInstitute->id, $result[3]->id);
    }

    /** @test */
    public function tools_that_are_not_published_in_the_generic_data_are_not_shown(): void
    {
        $institute = Institute::factory()->create();

        ToolHelper::create(published: false);
        ToolHelper::create($institute, false, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    public function tools_are_secondarily_sorted_by_english_name(): void
    {
        Tool::query()->delete();
        $institute = Institute::factory()->create();

        $tools = [
            ToolHelper::create($institute, true, ['name' => 'EEE'], [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ]),
            ToolHelper::create($institute, attributes: ['name' => 'CCC']),
            ToolHelper::create($institute, attributes: ['name' => 'BBB']),
        ];

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(3, $result);
        $this->assertEquals($tools[0]->id, $result[0]->id);
        $this->assertEquals($tools[2]->id, $result[1]->id);
        $this->assertEquals($tools[1]->id, $result[2]->id);
    }

    /** @test */
    public function institute_tools_without_status_are_put_just_before_non_institute_tools(): void
    {
        Tool::query()->delete();
        $institute = Institute::factory()->create();

        $tools = [
            ToolHelper::create($institute, true, ['name' => 'EEE'], [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ]),
            ToolHelper::create(attributes: ['name' => 'CCC']),
            ToolHelper::create(attributes: ['name' => 'BBB']),
            ToolHelper::create($institute, true, ['name' => 'FFF'], [
                'published_at' => Carbon::now(),
                'status'       => null,
            ]),
        ];

        $result = Tool::getToolsQueryForOverview($institute, [], '')->get();

        $this->assertCount(4, $result);
        $this->assertEquals($tools[0]->id, $result[0]->id);
        $this->assertEquals($tools[3]->id, $result[1]->id);
        $this->assertEquals($tools[2]->id, $result[2]->id);
        $this->assertEquals($tools[1]->id, $result[3]->id);
    }

    /**
     * @test
     *
     * @dataProvider globalSearchableFieldsProvider
     */
    public function tools_can_be_filtered_in_both_languages_through_search(string $field): void
    {
        $institute = Institute::factory()->create();

        if (in_array($field, Tool::getLocalizedFields())) {
            $field = $field . '_en';
        }

        $matchingTools = [
            ToolHelper::create($institute, true, [$field => 'Google searchterm assistant'], [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ]),
            ToolHelper::create(attributes: [$field => 'Astrology and more (searchterm edition)']),
        ];

        // Non-matching tools
        ToolHelper::create($institute, true, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);
        ToolHelper::create();
        // Does not match because it is not published
        ToolHelper::create($institute, false, [$field => 'Google searchterm assistant 2'], [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], 'searchterm')->get();

        $this->assertCount(2, $result);
        $this->assertEquals($matchingTools[0]->id, $result[0]->id);
        $this->assertEquals($matchingTools[1]->id, $result[1]->id);
    }

    /**
     * @test
     *
     * @dataProvider instituteSearchableLocalizedFieldsProvider
     */
    public function tools_can_be_searched_by_institute_specific_fields_in_both_languages(string $field): void
    {
        $institute = Institute::factory()->create();

        $matchingTools = [
            ToolHelper::create($institute, attributesInstitute: [
                $field . '_en' => 'Google searchterm assistant',
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ]),
        ];

        // Non-matching tools
        ToolHelper::create($institute, true, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ]);
        ToolHelper::create();
        // Doesn't match because it is not published in the institute.
        ToolHelper::create($institute, attributesInstitute: [
            $field . '_nl' => 'Astrology and more (searchterm edition)',
        ]);

        $result = Tool::getToolsQueryForOverview($institute, [], 'searchterm')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($matchingTools[0]->id, $result[0]->id);
    }

    /** @test */
    public function tools_can_be_filtered_by_tag_with_institute(): void
    {
        $institute = Institute::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $matchingTool = ToolHelper::create($institute, true, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], tags: [$tags[1]]);

        // Non-matching tools
        ToolHelper::create($institute, true, attributesInstitute: [
            'published_at' => Carbon::now(),
            'status'       => Status::ALLOWED,
        ], tags: [$tags[0]]);
        ToolHelper::create();

        $result = Tool::getToolsQueryForOverview($institute, [$tags[1]->id], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($matchingTool->id, $result[0]->id);
    }

    /** @test */
    public function tools_can_be_filtered_by_tag_without_institute(): void
    {
        $institute = Institute::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $matchingTool = ToolHelper::create(tags: [$tags[0]]);

        // Non-matching tools
        ToolHelper::create(tags: [$tags[1]]);
        ToolHelper::create();

        $result = Tool::getToolsQueryForOverview($institute, [$tags[0]->id], '')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($matchingTool->id, $result[0]->id);
    }

    /** @test */
    public function when_filtering_by_multiple_tags_it_must_match_all_of_them(): void
    {
        $institute = Institute::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        $matchingTools = [
            ToolHelper::create($institute, true, attributesInstitute: [
                'published_at' => Carbon::now(),
                'status'       => Status::ALLOWED,
            ], tags: [$tags[0], $tags[1]]),
            ToolHelper::create(tags: $tags->all()),
        ];

        // Non-matching tools
        ToolHelper::create(tags: [$tags[0]]);
        ToolHelper::create(tags: [$tags[1]]);
        ToolHelper::create(tags: [$tags[2]]);
        ToolHelper::create(tags: [$tags[0], $tags[2]]);
        ToolHelper::create(tags: [$tags[1], $tags[2]]);
        ToolHelper::create();

        $result = Tool::getToolsQueryForOverview($institute, [$tags[0]->id, $tags[1]->id], '')->get();

        $this->assertCount(count($matchingTools), $result);
        $this->assertEquals($matchingTools[0]->id, $result[0]->id);
        $this->assertEquals($matchingTools[1]->id, $result[1]->id);
    }

    /** @test */
    public function searching_and_filtering_can_be_used_together(): void
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

        $result = Tool::getToolsQueryForOverview($institute, [$tag->id], 'searchterm')->get();

        $this->assertCount(1, $result);
        $this->assertEquals($matchingTool->id, $result[0]->id);
    }

    public function globalSearchableFieldsProvider(): array
    {
        $fields = Tool::getSearchFields();

        return collect($fields)->map(fn (string $field) => [$field])->toArray();
    }

    public function instituteSearchableLocalizedFieldsProvider(): array
    {
        $fields = Arr::where(
            InstituteTool::getSearchFields(),
            fn (string $field) => in_array($field, InstituteTool::getLocalizedFields())
        );

        return collect($fields)->map(fn (string $field) => [$field])->toArray();
    }
}
