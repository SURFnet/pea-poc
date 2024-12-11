<?php

declare(strict_types=1);

namespace Tests\Feature\ContentManager\Tag;

use App\Enums\Tags\TagTypes;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function tags_are_listed(): void
    {
        $tag = Tag::factory()->create(['type' => TagTypes::FEATURES]);

        $this
            ->actingAs($this->contentManager)
            ->get(route('content-manager.tag.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tag/Index')
                    ->has(
                        'tags.data.0',
                        fn (Assert $page) => $page
                            ->where('name_array.en', $tag->getTranslation('name', 'en'))
                            ->etc()
                    )
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tag.index'));
    }

    /** @test */
    public function tags_can_be_filtered_by_name(): void
    {
        $tag = Tag::factory()
            ->create(['name' => ['en' => 'match_this_tag'], 'type' => TagTypes::FEATURES]);

        $this
            ->actingAs($this->contentManager)
            ->get(route('content-manager.tag.index', ['filter' => ['name_en' => 'match_this_tag']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tag/Index')
                    ->has('tags.data', 1)
                    ->has(
                        'tags.data.0',
                        fn (Assert $page) => $page
                            ->where('name_en', $tag->getTranslation('name', 'en'))
                            ->etc()
                    )
            );
    }
}
