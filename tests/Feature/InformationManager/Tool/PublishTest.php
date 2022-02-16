<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Enums\InstituteTool\Status;
use App\Models\Tool;
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
            'description_1' => ':: Description 1::',
            'status'        => Status::SUPPORTED,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.publish', $tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('institute_tool', array_merge($data, [
            'institute_id' => $this->informationManager->institute->id,
            'tool_id'      => $tool->id,
        ]));

        $instituteTool = $this->informationManager->institute->tools()->find($tool)->pivot;
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

            ->assertSessionHasErrors([
                'status' => trans('validation.required'),
            ]);
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
}
