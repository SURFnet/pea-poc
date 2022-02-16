<?php

declare(strict_types=1);

namespace Tests\Feature\Exception\Handler;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthorizationExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('authorization-test', function (): void {
            Gate::authorize('not-allowed');
        });
    }

    /** @test */
    public function gives_the_proper_response_for_a_user(): void
    {
        $this
            ->followingRedirects()
            ->actingAs($this->admin)
            ->post('authorization-test')

            ->assertSee(trans('message.error.unauthorized'));
    }

    /** @test */
    public function gives_the_proper_response_for_an_api(): void
    {
        $this
            ->actingAs($this->admin)
            ->postJson('authorization-test')

            ->assertStatus(403)
            ->assertSee(trans('message.error.api.unauthorized'));
    }
}
