<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Tool;

use App\Enums\Tool\SupportedStandard;
use Tests\Unit\Enums\BaseEnumTest;

class SupportedStandardTest extends BaseEnumTest
{
    /** @var SupportedStandard */
    protected $enum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enum = $this->app->make(SupportedStandard::class);
    }
}
