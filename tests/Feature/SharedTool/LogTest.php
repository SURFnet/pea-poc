<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool;

use App\Models\ToolLog;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class LogTest extends TestCase
{
    use HasToolTypeProvider;

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_page_can_be_visited(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->where('tool.name', $tool->name)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_logs_for_the_tool_are_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        $toolLog = ToolLog::factory()
            ->for($tool)
            ->for($this->actingUser)
            ->withoutInstitute()
            ->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->count('logs.data', 1)
                    ->where('logs.data.0.user.name', $this->actingUser->name)
                    ->where('logs.data.0.created_at', $toolLog->created_at->toW3cString())
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function logs_from_other_users_are_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        $toolLog = ToolLog::factory()
            ->for($tool)
            ->withoutInstitute()
            ->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->count('logs.data', 1)
                    ->where('logs.data.0.user.name', $toolLog->user->name)
                    ->where('logs.data.0.created_at', $toolLog->created_at->toW3cString())
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function logs_for_other_tools_are_not_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        ToolLog::factory()
            ->for($this->actingUser)
            ->withoutInstitute()
            ->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->count('logs.data', 0)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function logs_for_institutes_are_displayed_based_on_type(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        ToolLog::factory()
            ->for($tool)
            ->for($this->actingUser)
            ->withInstitute()
            ->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->count('logs.data', $isCustomTool ? 1 : 0)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function logs_are_sorted_by_newest_first(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->published()->create();

        $toolLogs = collect([
            '2022-03-05 10:00:00', // 4
            '2022-07-05 11:00:00', // 2
            '2022-07-05 14:00:00', // 1
            '2022-07-05 10:00:00', // 3
            '2023-07-05 11:00:00', // 0
        ])->map(fn (string $date) => ToolLog::factory()
            ->for($tool)
            ->withoutInstitute()
            ->create(['created_at' => $date]));

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->logRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Log')
                    ->count('logs.data', 5)
                    ->where('logs.data.0.user.name', $toolLogs[4]->user->name)
                    ->where('logs.data.1.user.name', $toolLogs[2]->user->name)
                    ->where('logs.data.2.user.name', $toolLogs[1]->user->name)
                    ->where('logs.data.3.user.name', $toolLogs[3]->user->name)
                    ->where('logs.data.4.user.name', $toolLogs[0]->user->name)
            );
    }
}
