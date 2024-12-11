<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tag;

use App\Enums\Tags\TagTypes;
use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_updated(): void
    {
        $tag = Tag::factory()
            ->withInstitute($this->informationManager->institute)
            ->create(['type' => TagTypes::CATEGORIES]);

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tag.update', $tag), [
                'name' => ['en' => '::edited_name::', 'nl' => '::edit_name_nl::'],
                'type' => $tag->type,
            ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('information-manager.tag.index'));

        $this->assertCount(1, Tag::findFromStringOfAnyType('::edited_name::'));
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_name_is_not_set(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $tag = Tag::factory()->withInstitute($this->informationManager->institute)->create();

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tag.update', $tag), [
                'name' => ['nl' => '::name_nl::'],
                'type' => 'features',
            ]);
    }

    /** @test */
    public function a_guest_cannot_update_a_category(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $tag = Tag::factory()->withInstitute($this->informationManager->institute)->create();

        $this
            ->put(route('information-manager.tag.update', $tag));
    }

    /** @test */
    public function a_institute_can_not_update_a_tag_from_another_institute(): void
    {
        $tag = Tag::factory()->withInstitute(Institute::factory()->create())->create();

        $this
            ->actingAs($this->informationManager)
            ->put(route('information-manager.tag.update', $tag), [
                'name' => ['en' => '::edited_name::', 'nl' => '::edit_name_nl::'],
                'type' => $tag->type,
            ])
            ->assertForbidden();
    }
}
