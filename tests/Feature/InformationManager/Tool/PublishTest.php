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

class PublishTest extends TestCase
{
    /** @test */
    public function changes_are_persisted_when_publishing(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $data = [
            ...$this->validRequestData(),
            'status'        => Status::ALLOWED,
            'conditions_en' => ':: Conditions EN::',
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('institute_tool', [
            'conditions_en' => ':: Conditions EN::',
            'status'        => Status::ALLOWED,
            'institute_id'  => $this->informationManager->institute->id,
            'tool_id'       => $tool->id,
        ]);

        $institute = $this->informationManager->institute;
        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();
        $this->assertTrue($instituteTool->is_published);
    }

    /** @test */
    public function a_status_is_required_when_publishing(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool, ['status' => null]);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool), [])

            ->assertSessionHasErrors(['status']);
    }

    /** @test */
    public function only_our_tools_can_be_published(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool));
    }

    /** @test */
    public function only_tools_that_are_published_by_cm_can_be_published_by_im(): void
    {
        $tool = Tool::factory()->published(false)->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool));
    }

    /** @test */
    public function a_teacher_can_not_publish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->put(route('information-manager.tool.publish', $tool));
    }

    /** @test */
    public function a_guest_can_not_publish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->put(route('information-manager.tool.publish', $tool));
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

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool), [
                ...$this->validRequestData(),
                'status'        => Status::ALLOWED,
                'conditions_en' => ':: Conditions EN::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertNull($pendingEdit->fresh());
    }

    /** @test */
    public function a_tool_log_will_be_created(): void
    {
        $tool = Tool::factory()->published()->create();
        $this->informationManager->institute->tools()->attach($tool);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool), [
                ...$this->validRequestData(),
                'status'        => Status::ALLOWED,
                'conditions_en' => ':: Conditions EN::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $log = ToolLog::first();

        $this->assertNotNull($log);
        $this->assertEquals($tool->id, $log->tool->id);
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
