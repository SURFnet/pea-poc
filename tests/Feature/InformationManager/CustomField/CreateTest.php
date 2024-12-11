<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\CustomField;

use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function the_page_can_be_visited(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('information-manager.custom-field.create'))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('information-manager/custom-field/Create')
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
