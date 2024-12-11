<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\Auth\Role;
use App\Models\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    /** @test */
    public function a_user_with_all_known_roles_is_super_admin(): void
    {
        /** @var User $user */
        $user = User::factory()->superAdmin()->create();
        $this->assertTrue($user->isSuperAdmin());
    }

    /** @test */
    public function a_user_without_all_known_roles_is_no_super_admin(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['roles' => [
            Role::TEACHER,
            Role::INFORMATION_MANAGER,
        ]]);

        $this->assertFalse($user->isSuperAdmin());
    }
}
