<?php

declare(strict_types=1);

namespace Tests\Feature\Tool;

use App\Models\Tool;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FollowTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider followingStatusProvider
     */
    public function it_shows_the_correct_follow_status(bool $following): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published()->create();

        $this->teacher->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        if ($following) {
            $tool->followers()->attach($this->teacher);
        }

        $this
            ->actingAs($this->teacher)
            ->get(route('our.tool.show', $tool))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('our/tool/Show')
                    ->where('following', $following)
            );
    }

    /**
     * @test
     *
     * @dataProvider followingStatusProvider
     */
    public function it_is_possible_to_toggle_following_status_for_a_tool(bool $following): void
    {
        /** @var Tool $tool */
        $tool = Tool::factory()->published()->create();

        $this->teacher->institute->tools()->attach($tool, [
            'published_at' => now(),
        ]);

        if ($following) {
            $tool->followers()->attach($this->teacher);
        }

        $this
            ->actingAs($this->teacher)
            ->post(route('tool.change-following-status', $tool))

            ->assertSessionDoesntHaveErrors();

        $this->assertEquals(!$following, $this->teacher->fresh()->isFollowingTool($tool));

        $this->assertEquals(
            session()->get('flash_notification')->first()->message,
            $following ? trans('message.stopped-following-tool') : trans('message.following-tool'),
        );
    }

    public function followingStatusProvider(): array
    {
        return [
            'following'     => ['following' => true],
            'not-following' => ['following' => false],
        ];
    }
}
