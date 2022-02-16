<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Category;

use App\Models\Category;
use App\Models\Institute;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_stored(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.category.store'), [
                'name'        => '::name::',
                'description' => '::description::',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.category.index'));

        $this->assertDatabaseHas('categories', [
            'name'         => '::name::',
            'description'  => '::description::',
            'institute_id' => $this->admin->institute->id,
        ]);
    }

    /** @test */
    public function the_name_needs_to_be_unique(): void
    {
        Category::factory()->for($this->admin->institute)->create(['name' => '::name::']);

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.category.store'), [
                'name'        => '::name::',
                'description' => '::description::',
            ])

            ->assertSessionHasErrors([
                'name' => trans('validation.unique'),
            ]);
    }

    /** @test */
    public function the_name_can_be_reused_across_institutes(): void
    {
        Category::factory()->for(Institute::factory()->create())->create(['name' => '::name::']);

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.category.store'), [
                'name'        => '::name::',
                'description' => '::description::',
            ])

            ->assertSessionDoesntHaveErrors();
    }

    /** @test */
    public function a_guest_can_not_store_a_category(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.category.store'));
    }
}
