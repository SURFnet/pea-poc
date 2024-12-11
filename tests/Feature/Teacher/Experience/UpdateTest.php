<?php

declare(strict_types=1);

namespace Tests\Feature\Teacher\Experience;

use App\Models\Experience;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_done_with_minimal_data(): void
    {
        $experience = Experience::factory()->for($this->teacher)->create([
            'title' => '::test_title::',
        ]);

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'title' => '::updated_test_title::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $experience->tool));

        $this->assertDatabaseHas('experiences', [
            'id'      => $experience->id,
            'user_id' => $this->teacher->id,
            'title'   => '::updated_test_title::',
        ]);
    }

    /** @test */
    public function can_be_done_with_all_data(): void
    {
        $experience = Experience::factory()->for($this->teacher)->create([
            'title'   => '::old-title::',
            'message' => '::old-tool_usage::',
        ]);

        $maxLengthMessage = str_repeat('a', config('validation.experience.message.max'));

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'title'   => '::title::',
                'message' => $maxLengthMessage,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $experience->tool));

        $this->assertDatabaseHas('experiences', [
            'id'      => $experience->id,
            'user_id' => $this->teacher->id,
            'title'   => '::title::',
            'message' => $maxLengthMessage,
        ]);
    }

    /** @test */
    public function the_message_can_not_be_longer_than_the_max(): void
    {
        $experience = Experience::factory()->for($this->teacher)->create();

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'message' => str_repeat('a', config('validation.experience.message.max') + 1),
            ])

            ->assertSessionHasErrors(['message']);
    }

    /** @test */
    public function a_teacher_can_not_update_an_experience_he_does_not_own(): void
    {
        $experience = Experience::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->teacher)
            ->put(route('teacher.experience.update', $experience));
    }

    /** @test */
    public function a_content_manager_can_not_store_an_experience(): void
    {
        $experience = Experience::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->contentManager)
            ->put(route('teacher.experience.update', $experience));
    }

    /** @test */
    public function an_information_manager_can_not_store_an_experience(): void
    {
        $experience = Experience::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->put(route('teacher.experience.update', $experience));
    }

    /** @test */
    public function a_guest_can_not_store_an_experience(): void
    {
        $experience = Experience::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->put(route('teacher.experience.update', $experience));
    }
}
