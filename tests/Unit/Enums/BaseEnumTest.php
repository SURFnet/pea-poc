<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\BaseEnum;
use Tests\TestCase;

abstract class BaseEnumTest extends TestCase
{
    /** @var BaseEnum */
    protected $enum;

    /** @test */
    public function the_translations_exist(): void
    {
        foreach ($this->enum->toArray() as $constant) {
            $key = $this->getProperty($this->enum, 'translationKey') . $constant;

            $this->assertNotSame($key, trans($key), 'The translation for "' . $key . '" is missing.');
        }
    }
}
