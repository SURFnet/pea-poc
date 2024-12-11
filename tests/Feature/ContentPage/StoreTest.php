<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function can_be_stored(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-page.store'), [
                'title_en' => '::title_en::',
                'body_en'  => '::body_en::',
                'slug'     => 'this-is-a-slug',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('content-page.index'));

        $this->assertCount(1, ContentPage::whereSlug('this-is-a-slug')->get());
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_required_fields_are_not_set(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-page.store'), [
            ])

            ->assertSessionHasErrors([
                'title_en' => trans('validation.required', ['attribute' => 'title en']),
                'body_en'  => trans('validation.required', ['attribute' => 'body en']),
                'slug'     => trans('validation.required', ['attribute' => 'slug']),
            ]);
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_slug_already_exists(): void
    {
        ContentPage::factory()->create(['slug' => 'this-is-a-slug']);

        $this
            ->actingAs($this->admin)
            ->post(route('content-page.store'), [
                'title_en' => '::title_en::',
                'body_en'  => '::body_en::',
                'slug'     => 'this-is-a-slug',
            ])

            ->assertSessionHasErrors([
                'slug' => trans('validation.unique', ['attribute' => 'slug']),
            ]);
    }

    /** @test */
    public function a_slug_will_be_formatted_correctly(): void
    {
        $this
            ->actingAs($this->admin)
            ->post(route('content-page.store'), [
                'title_en' => '::title_en::',
                'body_en'  => '::body_en::',
                'slug'     => 'This slug is formatted properly',
            ]);

        $this->assertCount(1, ContentPage::whereSlug('this-slug-is-formatted-properly')->get());
    }

    /** @test */
    public function an_information_manager_cannot_store_a_content_page(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->post(route('content-page.store'), [
                'body_en' => '::body_en::',
                'slug'    => '::this-is-a-slug::',
            ])

            ->assertForbidden();
    }

    /** @test */
    public function a_content_manager_cannot_store_a_content_page(): void
    {
        $this
            ->actingAs($this->contentManager)
            ->post(route('content-page.store'), [
                'body_en' => '::body_en::',
                'slug'    => '::this-is-a-slug::',
            ])

            ->assertForbidden();
    }
}
