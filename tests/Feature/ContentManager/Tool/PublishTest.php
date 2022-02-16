<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tool;

use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class PublishTest extends TestCase
{
    /** @test */
    public function a_tool_can_be_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $tool->toArray())

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertTrue($tool->refresh()->is_published);
    }

    /** @test */
    public function changes_are_persisted_when_publishing(): void
    {
        $tool = Tool::factory()->published(false)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), [
                'name'              => '::edited_name::',
                'description_short' => '::edited_description_short::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));

        $this->assertDatabaseHas('tools', [
            'id' => $tool->id,

            'name'              => '::edited_name::',
            'description_short' => '::edited_description_short::',
        ]);
    }

    /** @test */
    public function a_tool_can_not_be_published_again(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-manager.tool.publish', $tool), $tool->toArray())

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tool.index'));
    }
}
