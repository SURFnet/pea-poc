<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Edit')
                    ->where('tool.name', $tool->name)
            );
    }

    /** @test */
    public function the_page_can_only_be_visited_for_institute_owned_tools(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_only_be_visited_for_published_tools(): void
    {
        $tool = Tool::factory()->published(false)->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_teacher(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.tool.edit', $tool));
    }

    /** @test */
    public function a_pending_edit_is_created(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk();

        $pendingEdit = PendingToolEdit::first();

        $this->assertNotNull($pendingEdit);
        $this->assertEquals($tool->id, $pendingEdit->tool->id);
        $this->assertEquals($this->informationManager->id, $pendingEdit->user->id);
        $this->assertEquals($this->informationManager->institute->id, $pendingEdit->institute->id);
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
        $this->informationManager->institute->tools()->attach($tool);

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->informationManager->id,
            'institute_id' => $this->informationManager->institute->id,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNull($pendingEdit->fresh());
        $this->assertEquals(1, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_a_different_tool_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $pendingEdit = PendingToolEdit::factory([
            'user_id'      => $this->informationManager->id,
            'institute_id' => $this->informationManager->institute->id,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_a_different_user_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'institute_id' => $this->informationManager->institute->id,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_for_a_different_institute_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $pendingEdit = PendingToolEdit::factory([
            'tool_id' => $tool->id,
            'user_id' => $this->informationManager->id,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk();

        $this->assertNotNull($pendingEdit->fresh());
        $this->assertEquals(2, PendingToolEdit::count());
    }

    /** @test */
    public function a_pending_edit_without_an_institute_is_not_removed(): void
    {
        Carbon::setTestNow(Carbon::now());

        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->informationManager->id,
            'institute_id' => null,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

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
        $this->informationManager->institute->tools()->attach($tool);

        PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $otherUser->id,
            'institute_id' => $this->informationManager->institute->id,
        ])->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tool.edit', $tool))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tools/Edit')
                    ->where('pendingEdit.user.name', $otherUser->name)
            );
    }
}
