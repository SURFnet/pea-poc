<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use App\Models\CustomField;
use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $customField = CustomField::factory()->for($this->admin->institute)->create();

        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.custom-field.edit', $customField))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/custom-field/Edit')
                    ->where('customField.title', $customField->title_en)
                    ->where('customField.tab_type', $customField->tab_type)
            );
    }

    /** @test */
    public function the_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.custom-field.create'));
    }
}
