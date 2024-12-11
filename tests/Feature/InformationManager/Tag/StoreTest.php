<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tag;

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
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tag.store'), [
                'name' => ['en' => '::name::', 'nl' => '::name_en::'],
                'type' => TagTypes::CATEGORIES,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.tag.index'));

        $this->assertCount(1, Tag::findFromStringOfAnyType('::name::'));
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_name_is_not_set(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tag.store'), [
                'name' => ['nl' => '::name_nl::'],
                'type' => 'features',
            ]);
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_english_tag_already_exists(): void
    {
        Tag::factory()->withInstitute($this->informationManager->institute)->create([
            'name' => ['en' => '::name_en::'],
            'type' => TagTypes::CATEGORIES,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tag.store'), [
                'name' => ['en' => '::name_en::'],
                'type' => TagTypes::CATEGORIES,
            ]);
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_dutch_tag_already_exists(): void
    {
        Tag::factory()->withInstitute($this->informationManager->institute)->create([
            'name' => ['en' => '::name_enn::', 'nl' => '::name_nl::'],
            'type' => TagTypes::CATEGORIES,
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.tag.store'), [
                'name' => ['en' => '::name_en::', 'nl' => '::name_nl::'],
                'type' => TagTypes::CATEGORIES,
            ]);
    }

    /** @test */
    public function a_guest_can_not_store_a_tag(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.tag.store'));
    }
}
