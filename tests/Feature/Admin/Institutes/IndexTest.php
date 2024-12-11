<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Institutes;

use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function superadmins_can_use_this_module(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('admin.institutes.index'))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('institutes/Index')
            );
    }

    /** @test */
    public function non_superadmins_cannot_use_this_module(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->get(route('admin.institutes.index'));
    }
}
