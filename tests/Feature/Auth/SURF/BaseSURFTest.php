<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\SURF;

use Laravel\Socialite\Facades\Socialite;
use Tests\Fixtures\SURF\UserWithoutRoles;
use Tests\Fixtures\SURF\UserWithRoles;
use Tests\TestCase;

abstract class BaseSURFTest extends TestCase
{
    protected function asUserWithoutRoles(): self
    {
        Socialite::shouldReceive('driver')
            ->once()
            ->andReturn(new UserWithoutRoles());

        return $this;
    }

    protected function asUserWithRoles(): self
    {
        Socialite::shouldReceive('driver')
            ->once()
            ->andReturn(new UserWithRoles());

        return $this;
    }
}
