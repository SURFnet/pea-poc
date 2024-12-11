<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use App\Enums\Tool\Tabs;
use App\Models\CustomField;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function can_be_updated(): void
    {
        $customField = CustomField::factory()->for($this->admin->institute)->create(['tab_type' => Tabs::EDUCATION]);

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.custom-field.update', $customField), [
                'title_en' => '::edited_title::',
                'tab_type' => Tabs::SUPPORT,
            ])

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.custom-field.index'));

        $this->assertDatabaseHas('custom_fields', [
            'id'           => $customField->id,
            'title_en'     => '::edited_title::',
            'tab_type'     => Tabs::SUPPORT,
            'institute_id' => $this->admin->institute->id,
        ]);
    }

    /** @test */
    public function the_title_needs_to_be_unique_within_the_institute(): void
    {
        CustomField::factory()->for($this->admin->institute)->create(['title_en' => 'not-unique']);

        $customField = CustomField::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.custom-field.update', $customField), [
                'title_en' => 'not-unique',
            ])

            ->assertSessionHasErrors(['title_en']);
    }

    /** @test */
    public function a_guest_can_not_update_a_custom_field(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $customField = CustomField::factory()->create();

        $this
            ->put(route('information-manager.custom-field.update', $customField));
    }

    /** @test */
    public function the_tab_type_has_to_be_a_enum_value(): void
    {
        $customField = CustomField::factory()->for($this->admin->institute)->create(['tab_type' => '::tab_type::']);

        $this
            ->actingAs($this->admin)
            ->put(route('information-manager.custom-field.update', $customField), [
                'tab_type' => '::tab_type::',
            ])

            ->assertSessionHasErrors(['tab_type']);
    }
}
