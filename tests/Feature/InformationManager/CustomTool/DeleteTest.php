<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomTool;

use App\Models\Tool;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    private Tool $customTool;
    private Tool $regularTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customTool = Tool::factory()
            ->customTool($this->informationManager->institute)
            ->create();

        $this->regularTool = Tool::factory()->create();
    }

    /** @test */
    public function information_manager_can_delete_custom_tool(): void
    {
        $this->actingAs($this->informationManager)
            ->delete(route('information-manager.custom-tool.destroy', $this->customTool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.custom-tool.index'));

        $this->assertNull($this->customTool->fresh());

        $this->assertDatabaseMissing('tools', [
            'id' => $this->customTool->id,
        ]);
    }

    /** @test */
    public function flash_message_is_shown_after_successful_deletion(): void
    {
        $this->actingAs($this->informationManager)
            ->delete(route('information-manager.custom-tool.destroy', $this->customTool));

        $this->assertEquals(
            session('flash_notification')->first()->message,
            trans('message.entity-deleted', ['entity' => $this->customTool->name])
        );
    }

    /** @test */
    public function information_manager_cannot_delete_regular_tool(): void
    {
        $this->actingAs($this->informationManager)
            ->delete(route('information-manager.custom-tool.destroy', $this->regularTool))

            ->assertForbidden();

        $this->assertNotNull($this->regularTool->fresh());
    }

    /** @test */
    public function content_manager_cannot_delete_custom_tool(): void
    {
        $this->actingAs($this->contentManager)
            ->delete(route('information-manager.custom-tool.destroy', $this->customTool))

            ->assertForbidden();

        $this->assertNotNull($this->customTool->fresh());
    }

    /** @test */
    public function teacher_cannot_delete_custom_tool(): void
    {
        $this->actingAs($this->teacher)
            ->delete(route('information-manager.custom-tool.destroy', $this->customTool))

            ->assertForbidden();

        $this->assertNotNull($this->customTool->fresh());
    }

    /** @test */
    public function unauthenticated_user_cannot_delete_custom_tool(): void
    {
        $this->delete(route('information-manager.custom-tool.destroy', $this->customTool))

            ->assertRedirect(route('account.login'));

        $this->assertNotNull($this->customTool->fresh());
    }
}
