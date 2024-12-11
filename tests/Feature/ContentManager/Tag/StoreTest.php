<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tag;

use App\Enums\Tags\TagTypes;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_stored(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->post(route('content-manager.tag.store'), [
                'name' => ['en' => '::name::', 'nl' => '::name_en::'],
                'type' => TagTypes::FEATURES,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-manager.tag.index'));

        $this->assertCount(1, Tag::findFromStringOfAnyType('::name::'));
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_name_is_not_set(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->contentManager)
            ->post(route('content-manager.tag.store'), [
                'name' => ['nl' => '::name_nl::'],
                'type' => TagTypes::FEATURES,
            ]);
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_english_tag_already_exists(): void
    {
        Tag::factory()->create([
            'name' => ['en' => '::name_en::'],
            'type' => TagTypes::FEATURES,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->contentManager)
            ->post(route('content-manager.tag.store'), [
                'name' => ['en' => '::name_en::'],
                'type' => TagTypes::FEATURES,
            ]);
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_dutch_tag_already_exists(): void
    {
        Tag::factory()->create([
            'name' => ['en' => '::name_enn::', 'nl' => '::name_nl::'],
            'type' => TagTypes::FEATURES,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->contentManager)
            ->post(route('content-manager.tag.store'), [
                'name' => ['en' => '::name_en::', 'nl' => '::name_nl::'],
                'type' => TagTypes::FEATURES,
            ]);
    }

    /** @test */
    public function a_guest_can_not_store_a_tag(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('content-manager.tag.store'));
    }
}
