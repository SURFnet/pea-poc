<?php

declare(strict_types=1);

namespace Tests\Feature\Home;

use App\Enums\Tags\TagTypes;
use App\Models\Tag;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('home.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('home/Index')
            );
    }

    /** @test */
    public function it_contains_the_categories_for_the_institute(): void
    {
        Tag::factory(3)
            ->for($this->informationManager->institute)
            ->create([
                'type' => TagTypes::CATEGORIES,
            ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('home.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('home/Index')
                    ->has('categories', 3)
            );
    }

    /** @test */
    public function it_does_not_contain_categories_for_other_institutes(): void
    {
        Tag::factory(3)->create([
            'type' => TagTypes::CATEGORIES,
        ]);

        $this
            ->actingAs($this->informationManager)
            ->get(route('home.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('home/Index')
                    ->has('categories', 0)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('home.index'));
    }
}
