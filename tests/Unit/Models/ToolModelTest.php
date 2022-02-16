<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Experience;
use App\Models\Tool;
use Tests\TestCase;

class ToolModelTest extends TestCase
{
    /** @test */
    public function it_can_calculate_the_rating(): void
    {
        $tool = Tool::factory()->create();

        Experience::factory()->sequence(
            ['rating' => 2],
            ['rating' => 4],
        )->count(2)->for($tool)->create();

        $this->assertEquals(3, $tool->rating);
    }

    /** @test */
    public function the_rating_is_rounded_up(): void
    {
        $tool = Tool::factory()->create();

        Experience::factory()->sequence(
            ['rating' => 3],
            ['rating' => 5],
            ['rating' => 5],
        )->count(3)->for($tool)->create();

        $this->assertEquals(4, $tool->rating);
    }

    /** @test */
    public function if_no_experiences_are_given_the_rating_is_0(): void
    {
        $tool = Tool::factory()->create();

        $this->assertEquals(0, $tool->rating);
    }
}
