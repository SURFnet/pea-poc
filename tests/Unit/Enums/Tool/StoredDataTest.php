<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Tool;

use App\Enums\Tool\StoredData;
use Tests\Unit\Enums\BaseEnumTest;

class StoredDataTest extends BaseEnumTest
{
    /** @var StoredData */
    protected $enum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enum = $this->app->make(StoredData::class);
    }
}
