<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tag;

use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_be_deleted(): void
    {
        $tag = Tag::factory()->withInstitute($this->informationManager->institute)->create();

        $this
            ->actingAs($this->informationManager)
            ->delete(route('information-manager.tag.destroy', $tag))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tag.index'));

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
            ->delete(route('information-manager.tag.destroy', $tag));
    }

    /** @test */
    public function a_institute_can_not_delete_a_tag_from_another_institute(): void
    {
        $tag = Tag::factory()->withInstitute(Institute::factory()->create())->create();

        $this
            ->actingAs($this->informationManager)
            ->delete(route('information-manager.tag.destroy', $tag))
            ->assertForbidden();
    }
}
