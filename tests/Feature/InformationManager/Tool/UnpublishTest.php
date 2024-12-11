<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\InstituteTool;
use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UnpublishTest extends TestCase
{
    /** @test */
    public function changes_are_persisted_when_unpublishing(): void
    {
        $instituteTool = InstituteTool::factory()
            ->for(Tool::factory()->published()->create())
            ->for($this->informationManager->institute)
            ->published()
            ->create();

        $data = [
            ...$this->validRequestData(),
            'status'        => Status::ALLOWED,
            'conditions_en' => ':: Conditions EN::',
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.unpublish', $instituteTool->tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('concept_institute_tools', [
            'status'            => Status::ALLOWED,
            'conditions_en'     => ':: Conditions EN::',
            'institute_tool_id' => $instituteTool->id,
        ]);

        $this->assertFalse($instituteTool->fresh()->is_published);
    }

    /** @test */
    public function only_our_tools_can_be_unpublishd(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.unpublish', $tool));
    }

    /** @test */
    public function only_tools_that_are_published_by_cm_can_be_unpublished_by_im(): void
    {
        $tool = Tool::factory()->published(false)->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.unpublish', $tool));
    }

    /** @test */
    public function a_teacher_can_not_unpublish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->put(route('information-manager.tool.unpublish', $tool));
    }

    /** @test */
    public function a_guest_can_not_unpublish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->put(route('information-manager.tool.unpublish', $tool));
    }

    /** @test */
    public function an_existing_pending_edit_is_deleted(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);
        $pendingEdit = PendingToolEdit::factory([
            'tool_id'      => $tool->id,
            'user_id'      => $this->informationManager->id,
            'institute_id' => $this->informationManager->institute->id,
        ])->create();

        $data = [
            ...$this->validRequestData(),
            'status'        => Status::ALLOWED,
            'description_1' => ':: Description 1::',
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.unpublish', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertNull($pendingEdit->fresh());
    }

    /** @test */
    public function a_tool_log_will_be_created(): void
    {
        $instituteTool = InstituteTool::factory()
            ->for(Tool::factory()->published()->create())
            ->for($this->informationManager->institute)
            ->published()
            ->create();

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.unpublish', $instituteTool->tool), [
                ...$this->validRequestData(),
                'status'        => Status::ALLOWED,
                'conditions_en' => ':: Conditions EN::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($instituteTool->tool->id, $log->tool->id);
        $this->assertEquals($this->informationManager->id, $log->user->id);
        $this->assertEquals($this->informationManager->institute->id, $log->institute->id);
    }

    private function validRequestData(): array
    {
        return [
            'categories' => [],
        ];
    }
}
