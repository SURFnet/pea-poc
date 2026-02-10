<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool;

use Illuminate\Auth\AuthenticationException;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use HasToolTypeProvider;

    private function getCreateComponentPath(bool $isCustomTool): string
    {
        return $isCustomTool
            ? 'information-manager/custom-tools/Create'
            : 'content-manager/tool/Create';
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_page_can_be_visited(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this
            ->actingAs($this->admin)
            ->get(route($this->createRouteName))

            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getCreateComponentPath($isCustomTool))
            );
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function the_page_can_not_be_visited_by_a_guest(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route($this->createRouteName));
    }
}
