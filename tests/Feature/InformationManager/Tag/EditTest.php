<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tag;

use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $tag = Tag::factory()->withInstitute($this->informationManager->institute)->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tag.edit', $tag))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/tag/Edit')
                    ->where('tag.name_array.en', $tag->getTranslation('name', 'en'))
                    ->where('tag.type', $tag->type)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $tag = Tag::factory()->create();

        $this
            ->get(route('information-manager.tag.edit', $tag));
    }

    /** @test */
    public function a_institute_can_not_edit_a_tag_from_another_institute(): void
    {
        $tag = Tag::factory()->withInstitute(Institute::factory()->create())->create();

        $this
            ->actingAs($this->informationManager)
            ->get(route('information-manager.tag.edit', $tag))

            ->assertForbidden();
    }
}
