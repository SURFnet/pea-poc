<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use App\Models\CustomField;
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
            ->post(route('information-manager.custom-field.store'), [
                'title_en' => '::title::',
                'tab_type' => 'product',
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.custom-field.index'));

        $this->assertDatabaseHas('custom_fields', [
            'title_en'     => '::title::',
            'tab_type'     => 'product',
            'institute_id' => $this->admin->institute->id,
        ]);
    }

    /** @test */
    public function the_title_needs_to_be_unique_within_the_institute(): void
    {
        CustomField::factory()->for($this->admin->institute)->create(['title_en' => '::title::']);

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.custom-field.store'), [
                'title_en' => '::title::',
                'tab_type' => 'product',
            ])

            ->assertSessionHasErrors(['title_en']);
    }

    /** @test */
    public function the_title_can_be_reused_across_institutes(): void
    {
        CustomField::factory()->for(Institute::factory()->create())->create(['title_en' => '::title::']);

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.custom-field.store'), [
                'title_en' => '::title::',
                'tab_type' => 'product',
            ])

            ->assertSessionDoesntHaveErrors();
    }

    /** @test */
    public function the_tag_type_is_required(): void
    {
        CustomField::factory()->for(Institute::factory()->create())->create();

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.custom-field.store'), [
                'title_en' => '::title::',
            ])

            ->assertSessionHasErrors(['tab_type']);
    }

    /** @test */
    public function a_guest_can_not_store_a_custom_field(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->post(route('information-manager.custom-field.store'));
    }

    /** @test */
    public function the_tab_type_has_to_be_a_enum_value(): void
    {
        CustomField::factory()->for($this->admin->institute)->create(['tab_type' => '::tab_type::']);

        $this
            ->actingAs($this->admin)
            ->post(route('information-manager.custom-field.store'), [
                'tab_type' => '::tab_type::',
            ])

            ->assertSessionHasErrors(['tab_type']);
    }
}
