<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Tool;

use App\Enums\Tool\Status;
use Tests\Unit\Enums\BaseEnumTest;

class StatusTest extends BaseEnumTest
{
    /** @var Status */
    protected $enum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enum = $this->app->make(Status::class);
    }
}
