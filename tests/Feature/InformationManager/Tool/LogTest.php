<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Models\Tool;
use App\Models\ToolLog;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LogTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->where('tool.name', $tool->name)
            );
    }

    /** @test */
    public function the_logs_for_the_tool_are_displayed(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $toolLog = ToolLog::factory()
            ->for($tool)
            ->for($this->informationManager)
            ->for($this->informationManager->institute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->count('logs.data', 1)
                    ->where('logs.data.0.user.name', $this->informationManager->name)
                    ->where('logs.data.0.created_at', $toolLog->created_at->toW3cString())
            );
    }

    /** @test */
    public function logs_from_other_users_are_displayed(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $toolLog = ToolLog::factory()
            ->for($tool)
            ->for($this->informationManager->institute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->count('logs.data', 1)
                    ->where('logs.data.0.user.name', $toolLog->user->name)
                    ->where('logs.data.0.created_at', $toolLog->created_at->toW3cString())
            );
    }

    /** @test */
    public function logs_for_other_tools_are_not_displayed(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        ToolLog::factory()
            ->for($this->informationManager)
            ->for($this->informationManager->institute)
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->count('logs.data', 0)
            );
    }

    /** @test */
    public function logs_for_other_institutes_are_not_displayed(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        ToolLog::factory()
            ->for($tool)
            ->for($this->informationManager)
            ->withInstitute()
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->count('logs.data', 0)
            );
    }

    /** @test */
    public function logs_are_sorted_by_newest_first(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $toolLogs = collect([
            '2022-03-05 10:00:00', // 4
            '2022-07-05 11:00:00', // 2
            '2022-07-05 14:00:00', // 1
            '2022-07-05 10:00:00', // 3
            '2023-07-05 11:00:00', // 0
        ])->map(fn (string $date) => ToolLog::factory()
            ->for($tool)
            ->for($this->informationManager->institute)
            ->create(['created_at' => $date]));

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.log', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Log')
                    ->count('logs.data', 5)
                    ->where('logs.data.0.user.name', $toolLogs[4]->user->name)
                    ->where('logs.data.1.user.name', $toolLogs[2]->user->name)
                    ->where('logs.data.2.user.name', $toolLogs[1]->user->name)
                    ->where('logs.data.3.user.name', $toolLogs[3]->user->name)
                    ->where('logs.data.4.user.name', $toolLogs[0]->user->name)
            );
    }
}
