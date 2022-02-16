<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Category;

use App\Models\Category;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function categories_are_listed(): void
    {
        $category = Category::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.category.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/category/Index')
                    ->has(
                        'categories.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $category->name)
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
            ->get(route('information-manager.category.index'));
    }

    /** @test */
    public function categories_can_be_filtered_by_name(): void
    {
        Category::factory()->for($this->admin->institute)->create(['name' => 'irrelevant']);
        $category = Category::factory()->for($this->admin->institute)->create(['name' => 'matched']);

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.category.index', ['filter' => ['name' => 'match']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/category/Index')
                    ->has('categories.data', 1)
                    ->has(
                        'categories.data.0',
                        fn (Assert $page) => $page
                            ->where('name', $category->name)
                            ->etc()
                    )
            );
    }
}
