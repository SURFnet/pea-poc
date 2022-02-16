<?php

declare(strict_types=1);

namespace Tests\Feature\Exception\Handler;

use Tests\TestCase;

class NotFoundHttpExceptionTest extends TestCase
{
    /** @test */
    public function gives_the_proper_response_for_a_vistor(): void
    {
        $this
            ->get('/does/not/exist')

            ->assertStatus(404)
            ->assertViewIs('error')
            ->assertSee(trans('message.error.not-found'));
    }

    /** @test */
    public function gives_the_proper_response_for_an_api(): void
    {
        $this
            ->getJson('/does/not/exist')

            ->assertStatus(404)
            ->assertSee(trans('message.error.api.not-found'));
    }
}
