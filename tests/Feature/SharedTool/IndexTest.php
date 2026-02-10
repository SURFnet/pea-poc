<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool;

use App\Enums\Auth\Role;
use App\Enums\Tags\TagTypes;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Feature\SharedTool\Concerns\HasToolTypeProvider;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use HasToolTypeProvider;

    private function getIndexComponentPath(bool $isCustomTool): string
    {
        return $isCustomTool
            ? 'information-manager/custom-tools/Index'
            : 'content-manager/tool/Index';
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function tools_are_listed(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $tool = $this->baseFactory->create();

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->indexRouteName))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getIndexComponentPath($isCustomTool))
                    ->where('tools.data.0.id', $tool->id)
                    ->where('tools.data.0.name', $tool->name)
            );
    }

    public function indexAccessProvider(): array
    {
        $roles = [
            'admin',
            Role::CONTENT_MANAGER,
            Role::INFORMATION_MANAGER,
            Role::TEACHER,
        ];

        $routePermissions = [
            'content-manager.tool.index' => [
                'admin',
                Role::CONTENT_MANAGER,
            ],
            'information-manager.custom-tool.index' => [
                'admin',
                Role::INFORMATION_MANAGER,
            ],
        ];

        $testCases = [];

        foreach ($roles as $role) {
            foreach ($routePermissions as $route => $allowedRoles) {
                $isAllowed = in_array($role, $allowedRoles, true);
                $action = $isAllowed ? 'can' : 'cannot';
                $description = sprintf('%s %s access: %s', $role, $action, $route);

                $testCases[$description] = [
                    $role,
                    $route,
                    $isAllowed,
                ];
            }
        }

        return $testCases;
    }

    /**
     * @test
     *
     * @dataProvider indexAccessProvider
     */
    public function access_is_tested(string $role, string $routeName, bool $hasAccess): void
    {
        $user = match ($role) {
            'admin'                   => $this->admin,
            Role::CONTENT_MANAGER     => $this->contentManager,
            Role::INFORMATION_MANAGER => $this->informationManager,
            Role::TEACHER             => $this->teacher,
            default                   => throw new \UnhandledMatchError()
        };

        $this
            ->actingAs($user)
            ->get(route($routeName))

            ->when(
                $hasAccess,
                function (TestResponse $response): void {
                    $response->assertOk();
                },
                function (TestResponse $response): void {
                    $response->assertForbidden();
                }
            );
    }

    /** @test */
    public function the_tool_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('content-manager.tool.index'));
    }

    /** @test */
    public function the_custom_tool_page_can_not_be_visited_by_a_guest(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this
            ->get(route('information-manager.custom-tool.index'));
    }

    /**
     * @test
     *
     * @dataProvider toolTypeProvider
     */
    public function tools_can_be_filtered_by_name(bool $isCustomTool): void
    {
        $this->setupForToolType($isCustomTool);

        $this->baseFactory->create(['name' => 'irrelevant']);
        $tool = $this->baseFactory->create(['name' => 'matched']);

        $this
            ->actingAs($this->actingUser)
            ->get(route($this->indexRouteName, ['filter' => ['name' => 'match']]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component($this->getIndexComponentPath($isCustomTool))
                    ->has('tools.data', 1)
                    ->where('tools.data.0.id', $tool->id)
            );
    }

    /** @test */
    public function tools_can_be_filtered_by_feature(): void
    {
        Tool::factory()->create(['name' => 'irrelevant']);
        $tool = Tool::factory()->create(['name' => 'matched']);

        $featureTag = Tag::factory()->create([
            'type' => TagTypes::FEATURES,
        ]);

        $tool->syncTagsWithType([$featureTag], TagTypes::FEATURES);

        $this
            ->actingAs($this->admin)
            ->get(route('content-manager.tool.index', ['filter' => ['feature' => $featureTag->id]]))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('content-manager/tool/Index')
                    ->has('tools.data', 1)
                    ->where('tools.data.0.id', $tool->id)
            );
    }
}
