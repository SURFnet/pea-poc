<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\ContentManager\Tool;

use App\Models\Tool;
use Tests\TestCase;

class ConvertCustomToolTest extends TestCase
{
    private Tool $customTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customTool = Tool::factory()
            ->customTool($this->informationManager->institute)
            ->withInstituteTool()
            ->published()
            ->create();
    }

    /** @test */
    public function content_manager_can_convert_custom_tool_to_regular_tool(): void
    {
        $this->freezeTime();

        $this->actingAs($this->contentManager)
            ->post(route('content-manager.tool.convert', $this->customTool))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.edit', ['tool' => $this->customTool->refresh()]));

        $this->customTool->refresh();

        $this->assertDatabaseHas('tools', [
            'id'           => $this->customTool->id,
            'name'         => $this->customTool->name,
            'institute_id' => null,
            'published_at' => now(),
        ]);

        $this->assertDatabaseHas('institute_tool', [
            'tool_id'      => $this->customTool->id,
            'institute_id' => $this->informationManager->institute_id,
        ]);
    }

    /** @test */
    public function correct_slug_values_after_converting_custom_tool_to_regular_tool(): void
    {
        $oldSlug = $this->customTool->slug;
        $oldInstituteSlug = $this->customTool->institute_slug;

        $this->actingAs($this->contentManager)
            ->post(route('content-manager.tool.convert', $this->customTool))
            ->assertSessionDoesntHaveErrors();

        $this->customTool->refresh();

        $this->assertNotSame(
            $oldSlug,
            $this->customTool->slug,
            'Slug should have changed to a slug without the instituted prepended'
        );
        $this->assertSame(
            $oldInstituteSlug,
            $this->customTool->institute_slug,
            'This backwards compatibility slug should have stayed the same'
        );
    }

    /** @test */
    public function teacher_cannot_convert_custom_tool(): void
    {
        $this->actingAs($this->teacher)
            ->post(route('content-manager.tool.convert', $this->customTool))

            ->assertForbidden();
    }

    /** @test */
    public function information_manager_cannot_convert_custom_tool(): void
    {
        $this->actingAs($this->informationManager)
            ->post(route('content-manager.tool.convert', $this->customTool))

            ->assertForbidden();
    }

    /** @test */
    public function flash_message_is_shown_after_successful_conversion(): void
    {
        $this->actingAs($this->contentManager)
            ->post(route('content-manager.tool.convert', $this->customTool))

            ->assertSessionHas('flash_notification');

        $this->assertEquals(
            session('flash_notification')->first()->message,
            trans('message.tool-converted', ['entity' => $this->customTool->name])
        );
    }
}
