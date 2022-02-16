<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\LoginRedirect;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class LoginRedirectHelperTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_no_auth_user_is_found(): void
    {
        $this->expectException(AuthenticationException::class);

        LoginRedirect::doRedirect();
    }
}
