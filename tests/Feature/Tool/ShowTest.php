<?php

declare(strict_types=1);

namespace Tests\Feature\Tool;

use App\Models\Tool;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /** @test */
    public function users_are_redirected_to_the_other_tools_page(): void
    {
        $tool = Tool::factory()->published()->create();
        $users = $this->getUsers();

        foreach ($users as $user) {
            $this
                ->actingAs($user)
                ->get(route('tool.show', $tool))
                ->assertRedirect(route('other.tool.show', $tool));
        }
    }

    /** @test */
    public function users_are_redirected_to_the_our_tools_page(): void
    {
        $tool = Tool::factory()->published()->create();
        $users = $this->getUsers();

        foreach ($users as $user) {
            $user->institute->tools()->attach($tool, [
                'published_at' => now(),
            ]);

            $this
                ->actingAs($user)
                ->get(route('tool.show', $tool))
                ->assertRedirect(route('our.tool.show', $tool));
        }
    }

    /**
     * @return array<int, \App\Models\User>
     */
    private function getUsers(): array
    {
        return [
            $this->admin,
            $this->contentManager,
            $this->informationManager,
            $this->teacher,
        ];
    }
}
