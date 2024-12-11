<?php

declare(strict_types=1);

namespace Feature\ContentPage;

use App\Models\ContentPage;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_updated(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-page.update', $contentPage), [
                ...$contentPage->toArray(),
                'title_en' => '::edited_title::',
            ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('content-page.index'));

        $this->assertCount(1, ContentPage::whereTitleEn('::edited_title::')->get());
    }

    /** @test */
    public function a_validation_exception_is_thrown_when_the_required_fields_are_empty(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->admin)
            ->put(route('content-page.update', $contentPage), [
                'title_en' => null,
                'body_en'  => null,
                'slug'     => null,
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
        $contentPage = ContentPage::factory()->create();
        ContentPage::factory()->create(['slug' => 'this-is-a-slug']);

        $this
            ->actingAs($this->admin)
            ->put(route('content-page.update', $contentPage), [
                ...$contentPage->toArray(),
                'slug' => 'this-is-a-slug',
            ])

            ->assertSessionHasErrors([
                'slug' => trans('validation.unique', ['attribute' => 'slug']),
            ]);
    }

    /** @test */
    public function a_guest_cannot_update_a_content_page(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $contentPage = ContentPage::factory()->create();

        $this
            ->put(route('information-manager.tag.update', $contentPage));
    }

    /** @test */
    public function an_information_manager_cannot_update_a_content_page(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->informationManager)
            ->put(route('content-page.update', $contentPage), [
                'title_en' => '::title_en::',
                'body_en'  => '::body_en::',
                'slug'     => '::this-is-a-slug::',
            ])

            ->assertForbidden();
    }

    /** @test */
    public function a_content_manager_cannot_update_a_content_page(): void
    {
        $contentPage = ContentPage::factory()->create();

        $this
            ->actingAs($this->contentManager)
            ->put(route('content-page.update', $contentPage), [
                'title_en' => '::title_en::',
                'body_en'  => '::body_en::',
                'slug'     => '::this-is-a-slug::',
            ])

            ->assertForbidden();
    }
}
