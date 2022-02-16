<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\InstituteTool;

use App\Enums\InstituteTool\Status;
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

    /**
     * @test
     *
     * @dataProvider privateValues
     */
    public function it_removes_private_values_from_array_by_default(string $privateStatus): void
    {
        $values = $this->enum::toArray();

        $this->assertNotContains($privateStatus, $values);
    }

    /**
     * @test
     *
     * @dataProvider privateValues
     */
    public function it_removes_private_values_from_select_by_default(string $privateStatus): void
    {
        $values = $this->enum::asSelect();

        $this->assertArrayNotHasKey($privateStatus, $values);
    }

    /**
     * @test
     *
     * @dataProvider privateValues
     */
    public function it_includes_private_values_in_filter_select(string $privateStatus): void
    {
        $values = $this->enum::asFilterSelect();

        $this->assertArrayHasKey($privateStatus, $values);
    }

    public function privateValues(): array
    {
        return [
            [Status::PROHIBITED],
            [Status::UNPUBLISHED],
            [Status::UNRATED],
        ];
    }
}
