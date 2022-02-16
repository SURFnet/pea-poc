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
            'rating' => 1,
        ]);

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'rating' => 4,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $experience->tool));

        $this->assertDatabaseHas('experiences', [
            'id'      => $experience->id,
            'user_id' => $this->teacher->id,
            'rating'  => 4,
        ]);
    }

    /** @test */
    public function can_be_done_with_all_data(): void
    {
        $experience = Experience::factory()->for($this->teacher)->create([
            'rating'  => 2,
            'title'   => '::old-title::',
            'message' => '::old-message::',
        ]);

        $maxMessage = str_repeat('a', config('validation.experience.message.max'));

        $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'rating'  => 4,
                'title'   => '::title::',
                'message' => $maxMessage,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('our.tool.show', $experience->tool));

        $this->assertDatabaseHas('experiences', [
            'id'      => $experience->id,
            'user_id' => $this->teacher->id,
            'rating'  => 4,
            'title'   => '::title::',
            'message' => $maxMessage,
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
                'rating'  => 4,
                'message' => str_repeat('a', config('validation.experience.message.max') + 1),
            ])

            ->assertSessionHasErrors(['message']);
    }

    /**
     * @test
     *
     * @dataProvider ratingData
     */
    public function the_rating_needs_to_be_valid(int $rating, bool $isValid): void
    {
        $experience = Experience::factory()->for($this->teacher)->create();

        $request = $this
            ->actingAs($this->teacher)
            ->from(route('our.tool.show', $experience->tool))
            ->put(route('teacher.experience.update', $experience), [
                'rating' => $rating,
            ]);

        if (!$isValid) {
            $request->assertSessionHasErrors('rating');

            return;
        }

        $request
                ->assertSessionDoesntHaveErrors()
                ->assertRedirect(route('our.tool.show', $experience->tool));
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

    public function ratingData(): array
    {
        return [
            [1, true],
            [2, true],
            [3, true],
            [4, true],
            [5, true],

            [-1, false],
            [0, false],
            [6, false],
            [500, false],
        ];
    }
}
