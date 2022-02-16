<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Enums\InstituteTool\Status;
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
        $tool = Tool::factory()->published()->create();
        $institute = $this->informationManager->institute;

        InstituteTool::factory()
            ->for($tool)
            ->for($institute)
            ->published(false)
            ->create([
                'status' => $status,
            ]);

        $this->assertFalse($tool->institutesThatUseTool()->get()->contains($institute));
    }

    /**
     * @dataProvider whitelistedStatuses
     *
     * @test
     */
    public function institutes_that_have_published_tool_with_whitelisted_status_are_visible(string $status): void
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

        $this->assertTrue($tool->institutesThatUseTool()->get()->contains($institute));
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

        $this->assertFalse($tool->institutesThatUseTool()->get()->contains($institute));
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

        $this->assertFalse($tool->institutesThatUseTool()->get()->contains($institute));
    }

    public function whitelistedStatuses(): array
    {
        return [
            [Status::RECOMMENDED],
            [Status::SUPPORTED],
            [Status::FREE_TO_USE],
        ];
    }

    public function blacklistedStatuses(): array
    {
        return [
            [Status::NOT_RECOMMENDED],
            [Status::PROHIBITED],
            [Status::UNRATED],
            [Status::UNPUBLISHED],
        ];
    }
}
