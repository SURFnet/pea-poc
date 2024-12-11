<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use App\Models\CustomField;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_be_deleted(): void
    {
        $customField = CustomField::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->delete(route('information-manager.custom-field.destroy', $customField))

            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('information-manager.custom-field.index'));

        $this->assertDatabaseMissing('custom_fields', [
            'id' => $customField->id,
        ]);
    }

    /** @test */
    public function a_guest_can_not_delete_a_custom_field(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $customField = CustomField::factory()->create();

        $this
            ->delete(route('information-manager.custom-field.destroy', $customField));
    }
}
