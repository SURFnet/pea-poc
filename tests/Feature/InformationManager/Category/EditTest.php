<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Category;

use App\Models\Category;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $category = Category::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.category.edit', $category))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/category/Edit')
                    ->where('category.name', $category->name)
                    ->where('category.description', $category->description)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.category.create'));
    }
}
