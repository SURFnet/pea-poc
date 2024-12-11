<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use App\Models\CustomField;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function custom_fields_are_listed(): void
    {
        $customField = CustomField::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.custom-field.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/custom-field/Index')
                    ->has(
                        'customFields.data.0',
                        fn (Assert $page) => $page
                            ->where('title', $customField->title_en)
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
            ->get(route('information-manager.custom-field.index'));
    }

    /** @test */
    public function custom_fields_can_be_filtered_by_name(): void
    {
        CustomField::factory()->for($this->admin->institute)->create(['title_en' => 'irrelevant']);
        $customField = CustomField::factory()->for($this->admin->institute)->create(['title_en' => 'matched']);

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.custom-field.index', ['filter' => ['title' => 'match']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/custom-field/Index')
                    ->has('customFields.data', 1)
                    ->has(
                        'customFields.data.0',
                        fn (Assert $page) => $page
                            ->where('title', $customField->title_en)
                            ->etc()
                    )
            );
    }

    /** @test */
    public function name_and_description_are_translated(): void
    {
        $this->app->setLocale('nl');

        $customField = CustomField::factory()->for($this->admin->institute)->create([
            'title_nl' => 'Dutch title',
        ]);

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.custom-field.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/custom-field/Index')
                    ->has(
                        'customFields.data.0',
                        fn (Assert $page) => $page
                            ->where('title', $customField->title_nl)
                            ->etc()
                    )
            );
    }
}
