<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tag;

use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_be_deleted(): void
    {
        $tag = Tag::factory()->create();

        $this
            ->actingAs($this->contentManager)
            ->delete(route('content-manager.tag.destroy', $tag))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tag.index'));

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }

    /** @test */
    public function a_guest_can_not_delete_a_tag(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $tag = Tag::factory()->create();

        $this
            ->delete(route('content-manager.tag.destroy', $tag));
    }

    /** @test */
    public function a_information_manager_can_not_delete_a_tag(): void
    {
        $tag = Tag::factory()->create();

        $this
            ->actingAs($this->informationManager)
            ->delete(route('content-manager.tag.destroy', $tag))
            ->assertForbidden();
    }
}
