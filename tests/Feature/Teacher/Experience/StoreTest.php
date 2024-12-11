<?php

declare(strict_types=1);

namespace Tests\Feature\Teacher\Experience;

use App\Models\Tool;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_done_with_minimal_data(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $tool))
            ->post(route('teacher.tool.experience.store', $tool), [
                'title' => '::test_title::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $tool));

        $this->assertDatabaseHas('experiences', [
            'tool_id' => $tool->id,
            'user_id' => $this->teacher->id,
            'title'   => '::test_title::',
        ]);
    }

    /** @test */
    public function can_be_done_with_all_data(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $maxLengthMessage = str_repeat('a', config('validation.experience.message.max'));

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $tool))
            ->post(route('teacher.tool.experience.store', $tool), [
                'title'   => '::title::',
                'message' => $maxLengthMessage,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $tool));

        $this->assertDatabaseHas('experiences', [
            'tool_id' => $tool->id,
            'user_id' => $this->teacher->id,
            'title'   => '::title::',
            'message' => $maxLengthMessage,
        ]);
    }

    /** @test */
    public function the_message_can_not_be_longer_than_the_max(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $tool))
            ->post(route('teacher.tool.experience.store', $tool), [
                'message' => str_repeat('a', config('validation.experience.message.max') + 1),
            ])

            ->assertSessionHasErrors(['message']);
    }

    /** @test */
    public function a_content_manager_can_not_store_an_experience(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->contentManager)
            ->post(route('teacher.tool.experience.store', $tool));
    }

    /** @test */
    public function an_information_manager_can_not_store_an_experience(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('teacher.tool.experience.store', $tool));
    }

    /** @test */
    public function a_guest_can_not_store_an_experience(): void
    {
        $tool = Tool::factory()->published(true)->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('teacher.tool.experience.store', $tool));
    }
}
