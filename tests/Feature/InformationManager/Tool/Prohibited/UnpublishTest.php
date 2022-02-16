<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool\Prohibited;

use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UnpublishTest extends TestCase
{
    /** @test */
    public function changes_are_persisted_when_unpublishing(): void
    {
        $alternativeTool = Tool::factory()->published()->create();
        InstituteTool::factory()
            ->for($alternativeTool)
            ->for($this->informationManager->institute)
            ->published()
            ->create();
        $instituteTool = InstituteTool::factory()
            ->for(Tool::factory()->published()->create())
            ->for($this->informationManager->institute)
            ->published()
            ->create();

        $data = [
            'why_unfit'           => '::why-unfit::',
            'alternative_tool_id' => $alternativeTool->id,
        ];

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tool.prohibited.unpublish', $instituteTool->tool), $data)

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tool.index'));

        $this->assertDatabaseHas('institute_tool', array_merge($data, [
            'institute_id' => $this->informationManager->institute->id,
            'tool_id'      => $instituteTool->tool->id,
        ]));

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
            ->put(route('information-manager.tool.prohibited.unpublish', $tool));
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
            ->put(route('information-manager.tool.prohibited.unpublish', $tool));
    }

    /** @test */
    public function a_teacher_can_not_unpublish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->put(route('information-manager.tool.prohibited.unpublish', $tool));
    }

    /** @test */
    public function a_guest_can_not_unpublish_a_tool(): void
    {
        $tool = Tool::factory()->published()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->put(route('information-manager.tool.prohibited.unpublish', $tool));
    }
}
