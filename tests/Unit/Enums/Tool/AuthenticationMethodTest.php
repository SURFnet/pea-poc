<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Tool;

use App\Enums\Tool\AuthenticationMethod;
use Tests\Unit\Enums\BaseEnumTest;

class AuthenticationMethodTest extends BaseEnumTest
{
    /** @var AuthenticationMethod */
    protected $enum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enum = $this->app->make(AuthenticationMethod::class);
    }
}
