<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\SURF;

use App\Models\Institute;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CallbackTest extends BaseSURFTest
{
    /** @test */
    public function a_user_without_roles_is_not_allowed_to_login(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->asUserWithoutRoles()
            ->get(route('auth.surf.callback'));
    }

    /** @test */
    public function will_fail_if_the_institute_is_missing(): void
    {
        Institute::query()->delete();

        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        $this
            ->asUserWithRoles()
            ->get(route('auth.surf.callback'));
    }

    /** @test */
    public function a_user_with_roles_and_proper_institute_can_login(): void
    {
        Institute::query()->delete();

        Institute::factory()->create(['domain' => 'eduid.nl']);

        $this
            ->asUserWithRoles()
            ->get(route('auth.surf.callback'))

            ->assertRedirect(route('home.index'));
    }
}
