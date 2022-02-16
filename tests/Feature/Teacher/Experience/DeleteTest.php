<?php

declare(strict_types=1);

namespace Tests\Feature\Teacher\Experience;

use App\Models\Experience;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function a_teacher_can_delete_his_own_experience(): void
    {
        $experience = Experience::factory()->for($this->teacher)->create();

        $this
            ->from(route('our.tool.show', $experience->tool))
            ->actingAs($this->teacher)
            ->delete(route('teacher.experience.destroy', $experience))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $experience->tool));

        $this->assertDatabaseMissing('experiences', [
            'id' => $experience->id,
        ]);
    }

    /** @test */
    public function a_teacher_can_not_delete_an_experience_he_does_not_own(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $experience = Experience::factory()->create();

        $this
            ->actingAs($this->teacher)
            ->delete(route('teacher.experience.destroy', $experience));
    }

    /** @test */
    public function a_guest_can_not_delete_an_experience(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $experience = Experience::factory()->create();

        $this
            ->delete(route('teacher.experience.destroy', $experience));
    }
}
