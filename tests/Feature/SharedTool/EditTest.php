<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool;

use App\Models\Institute;
use App\Models\PendingToolEdit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class EditTest extends TestCase
{
    use HasToolTypeProvider;

    private function getEditComponentPath(bool $isCustomTool): string
    {
        return $isCustomTool
            ? 'information-manager/custom-tools/Edit'
            : 'content-manager/tool/Edit';
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_page_can_be_visited(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('tool.name', $tool->name)
                    ->where('tool.description_short_en', $tool->description_short_en)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function can_not_be_visited_by_a_guest(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $tool = $this->baseFactory->create();

        $this
            ->get(route($this->editRouteName, $tool));
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_is_created(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool));

        $pendingEdit = PendingToolEdit::first();

        $this->assertNotNull($pendingEdit);
        $this->assertEquals($tool->id, $pendingEdit->tool->id);
        $this->assertEquals($this->actingUser->id, $pendingEdit->user->id);
        $this->assertEquals(
            Carbon::now()->format(config('constants.format.datetime')),
            $pendingEdit->created_at->format(config('constants.format.datetime'))
        );

        if ($isCustomTool) {
            $this->assertTrue($pendingEdit->institute->is($this->actingUser->institute));
        } else {
            $this->assertNull($pendingEdit->institute);
        }
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function an_existing_pending_edit_is_replaced_with_a_new_one(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->actingUser->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk();

        $this->assertNull($pendingEdit->fresh());
        $this->assertEquals(1, PendingToolEdit::count());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_for_a_different_tool_is_not_removed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'user_id'      => $this->actingUser->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_for_a_different_user_is_not_removed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_for_an_institute_is_not_removed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();
        $institute = Institute::factory()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->actingUser->id,
            'institute_id' => $institute->id,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function an_existing_pending_edit_is_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $otherUser = User::factory()->create([
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ]);
        $tool = $this->baseFactory->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $otherUser->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('pendingEdit.user.name', $otherUser->name)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_from_the_same_user_is_not_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->actingUser->id,
            'institute_id' => $isCustomTool ? $this->actingUser->institute_id : null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('pendingEdit', null)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_for_an_institute_is_not_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();
        $institute = Institute::factory()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => $institute->id,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('pendingEdit', null)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function a_pending_edit_for_a_different_tool_is_not_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        PendingToolEdit::factory([
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('pendingEdit', null)
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function an_old_pending_edit_is_not_displayed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        Config::set('session.lifetime', 10);
        Carbon::setTestNow(Carbon::now());

        $tool = $this->baseFactory->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => null,
            'created_at'   => Carbon::now()->subMinutes(15),
        ])->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->editRouteName, $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getEditComponentPath($isCustomTool))
                    ->where('pendingEdit', null)
            );
    }
}
