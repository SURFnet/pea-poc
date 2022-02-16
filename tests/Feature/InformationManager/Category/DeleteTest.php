<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Category;

use App\Models\Category;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_be_deleted(): void
    {
        $category = Category::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->delete(route('information-manager.category.destroy', $category))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.category.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /** @test */
    public function a_guest_can_not_delete_a_category(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $category = Category::factory()->create();

        $this
            ->delete(route('information-manager.category.destroy', $category));
    }
}
