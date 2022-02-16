<?php

declare(strict_types=1);

namespace Tests\Feature\Exception\Handler;

use Tests\TestCase;

class AuthenticationExceptionTest extends TestCase
{
    /** @test */
    public function gives_the_proper_response_for_a_vistor(): void
    {
        $this
            ->get(route('home.index'))

            ->assertRedirect(route('account.login'));
    }

    /** @test */
    public function gives_the_proper_response_for_an_api(): void
    {
        $this
            ->getJson(route('home.index'))

            ->assertStatus(401)
            ->assertSee(trans('message.error.api.unauthenticated'));
    }
}
