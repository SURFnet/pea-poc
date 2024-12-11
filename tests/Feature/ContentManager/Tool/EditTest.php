<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Models\Institute;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('tool.name', $tool->name)
                    ->where('tool.description_short_en', $tool->description_short_en)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tool.create'));
    }

    /** @test */
    public function a_pending_edit_is_created(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool));

        $pendingEdit = PendingToolEdit::first();

        $this->assertNotNull($pendingEdit);
        $this->assertEquals($tool->id, $pendingEdit->tool->id);
        $this->assertEquals($this->admin->id, $pendingEdit->user->id);
        $this->assertEquals(null, $pendingEdit->institute);
        $this->assertEquals(
            Carbon::now()->format(config('constants.format.datetime')),
            $pendingEdit->created_at->format(config('constants.format.datetime'))
        );
    }

    /** @test */
    public function an_existing_pending_edit_is_replaced_with_a_new_one(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->admin->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNull($pendingEdit->fresh());
        $this->assertEquals(1, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_a_different_tool_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'user_id'      => $this->admin->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_a_different_user_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_an_institute_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $institute = Institute::factory()->create();

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->admin->id,
            'institute_id' => $institute->id,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function an_existing_pending_edit_is_displayed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $otherUser = User::factory()->create();
        $tool = Tool::factory()->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $otherUser->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('pendingEdit.user.name', $otherUser->name)
            );
    }

    /** @test */
    public function a_pending_edit_from_the_same_user_is_not_displayed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->admin->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('pendingEdit', null)
            );
    }

    /** @test */
    public function a_pending_edit_for_an_institute_is_not_displayed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $institute = Institute::factory()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => $institute->id,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('pendingEdit', null)
            );
    }

    /** @test */
    public function a_pending_edit_for_a_different_tool_is_not_displayed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        PendingToolEdit::factory([
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('pendingEdit', null)
            );
    }

    /** @test */
    public function an_old_pending_edit_is_not_displayed(): void
    {
        Config::set('session.lifetime', 10);
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => null,
            'created_at'   => Carbon::now()->subMinutes(15),
        ])->create();

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Edit')
                    ->where('pendingEdit', null)
            );
    }
}
