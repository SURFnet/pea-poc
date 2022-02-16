<?php

declare(strict_types=1);

namespace Tests\Unit\Rule;

use App\Rules\DbString;
use Illuminate\Support\Str;
use Tests\TestCase;

class DbStringTest extends TestCase
{
    protected DbString $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new DbString();
    }

    /**
     * @test
     *
     * @dataProvider validData
     */
    public function it_passes(string $string): void
    {
        $this->assertTrue($this->rule->passes('test', $string));
    }

    /** @test */
    public function it_fails(): void
    {
        $longString = Str::random(config('validation.db_string.length') + 1);

        $this->assertFalse($this->rule->passes('test', $longString));
    }

    public function validData(): array
    {
        return [
            ['123'],
            ['asd'],
            [''],
        ];
    }
}
