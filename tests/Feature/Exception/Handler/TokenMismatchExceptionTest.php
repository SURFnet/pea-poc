<?php

declare(strict_types=1);

namespace Tests\Feature\Exception\Handler;

use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class TokenMismatchExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->partialMock(VerifyCsrfToken::class, function ($mock): void {
            $mock
                ->shouldAllowMockingProtectedMethods()
                ->allows(['runningUnitTests' => false]);
        });
    }

    /** @test */
    public function gives_the_proper_response_for_a_vistor(): void
    {
        $this
            ->followingRedirects()
            ->post(route('account.login-as-super-admin'), [
                '_token' => 'INVALID_TOKEN',
                'email'  => 'admin@paqt.com',
            ])

            ->assertSee(trans('message.error.inactivity'));
    }

    /** @test */
    public function gives_the_proper_response_for_an_api(): void
    {
        $this
            ->postJson(route('account.login-as-super-admin'), [
                '_token' => 'INVALID_TOKEN',
                'email'  => 'admin@paqt.com',
            ])

            ->assertStatus(419)
            ->assertSee(trans('message.error.api.csrf-failed'));
    }
}
