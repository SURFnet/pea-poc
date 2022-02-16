<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Category;

use App\Models\Category;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_updated(): void
    {
        $category = Category::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.category.update', $category), [
                'name'        => '::edited_name::',
                'description' => '::edited_description::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.category.index'));

        $this->assertDatabaseHas('categories', [
            'id'           => $category->id,
            'name'         => '::edited_name::',
            'description'  => '::edited_description::',
            'institute_id' => $this->admin->institute->id,
        ]);
    }

    /** @test */
    public function the_name_needs_to_be_unique(): void
    {
        Category::factory()->for($this->admin->institute)->create(['name' => 'not-unique']);

        $category = Category::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.category.update', $category), [
                'name'        => 'not-unique',
                'description' => '::description::',
            ])

            ->assertSessionHasErrors([
                'name' => trans('validation.unique'),
            ]);
    }

    /** @test */
    public function a_guest_can_not_update_a_category(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $category = Category::factory()->create();

        $this
            ->put(route('information-manager.category.update', $category));
    }
}
