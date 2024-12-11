<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Tests\TestCase;

class InstitutesThatUseToolTest extends TestCase
{
    /**
     * @dataProvider whitelistedStatuses
     *
     * @test
     */
    public function institutes_that_have_unpublished_tool_with_whitelisted_status_are_hidden(string $status): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published()->create();
        $institute = $this->informationManager->institute;

        InstituteTool::factory()
            ->for($tool)
            ->for($institute)
            ->published(false)
            ->create([
                'status' => $status,
            ]);

        $institutesUsingTool = Institute::usingTool($tool)->pluck('institutes.id');
        $this->assertNotContains($institute->id, $institutesUsingTool);
    }

    /**
     * @dataProvider whitelistedStatuses
     *
     * @test
     */
    public function institutes_that_have_published_tool_with_whitelisted_status_are_visible(string $status): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published()->create();
        $institute = $this->informationManager->institute;

        InstituteTool::factory()
            ->for($tool)
            ->for($institute)
            ->published()
            ->create([
                'status' => $status,
            ]);

        $institutesUsingTool = Institute::usingTool($tool)->pluck('institutes.id');
        $this->assertContains($institute->id, $institutesUsingTool);
    }

    /**
     * @dataProvider blacklistedStatuses
     *
     * @test
     */
    public function institutes_that_have_unpublished_tool_with_blacklisted_status_are_hidden(string $status): void
    {
        $tool = Tool::factory()->published()->create();
        $institute = $this->informationManager->institute;

        InstituteTool::factory()
            ->for($tool)
            ->for($institute)
            ->published(false)
            ->create([
                'status' => $status,
            ]);

        $institutesUsingTool = Institute::usingTool($tool)->pluck('institutes.id');
        $this->assertNotContains($institute->id, $institutesUsingTool);
    }

    /**
     * @dataProvider blacklistedStatuses
     *
     * @test
     */
    public function institutes_that_have_published_tool_with_blacklisted_status_are_hidden(string $status): void
    {
        $tool = Tool::factory()->published()->create();
        $institute = $this->informationManager->institute;

        InstituteTool::factory()
            ->for($tool)
            ->for($institute)
            ->published()
            ->create([
                'status' => $status,
            ]);

        $institutesUsingTool = Institute::usingTool($tool)->pluck('institutes.id');
        $this->assertNotContains($institute->id, $institutesUsingTool);
    }

    public function whitelistedStatuses(): array
    {
        return [
            [Status::ALLOWED],
            [Status::ALLOWED_UNDER_CONDITIONS],
        ];
    }

    public function blacklistedStatuses(): array
    {
        return [
            [Status::DISALLOWED],
            [Status::UNRATED],
            [Status::UNPUBLISHED],
        ];
    }
}
