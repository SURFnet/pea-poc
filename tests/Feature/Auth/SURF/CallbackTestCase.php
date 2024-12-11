<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\SURF;

use App\Exceptions\SurfConextException;
use App\Models\Institute;

class CallbackTestCase extends BaseSURFTestCase
{
    /** @test */
    public function will_fail_if_the_institute_is_missing(): void
    {
        Institute::query()->delete();

        $this->withoutExceptionHandling();
        $this->expectException(SurfConextException::class);
        $this->expectExceptionMessage(trans('message.error.login.unknown_institute'));

        $this
            ->asUserWithRoles()
            ->get(route('auth.surf.callback'));
    }

    /** @test */
    public function will_fail_if_user_does_not_have_correct_affiliation(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(SurfConextException::class);
        $this->expectExceptionMessage(trans('message.error.login.invalid_affiliation'));

        $this
            ->asUserWithoutEmployeeAffiliation()
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
