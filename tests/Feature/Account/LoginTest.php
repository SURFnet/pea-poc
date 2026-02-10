<?php

declare(strict_types=1);

namespace Tests\Feature\Account;

use App\Enums\Auth\Role;
use App\Http\Middleware\VerifyCsrfToken;
use Inertia\Testing\AssertableInertia as Assert;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function has_the_option_to_login_as_test_user_on_development_environments(): void
    {
        $this
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->where('allowTestUserLogin', true)
            );
    }

    /**
     * @test
     *
     * @dataProvider userRolesProvider
     */
    public function can_login_as_role_on_development_environments(string $role): void
    {
        $this->withoutExceptionHandling();
        $this
            ->post(route('account.login-as-test-user'), [
                'role' => $role,
            ])

            ->assertRedirect();

        $this->assertAuthenticated();
    }

    /** @test */
    public function upon_login_the_locale_is_applied(): void
    {
        $this->admin->language = 'nl';
        $this->admin->save();

        $this
            ->post(route('account.login-as-test-user'), [
                'role' => 'admin',
            ])
            ->assertRedirect(LaravelLocalization::localizeUrl(route('home.index'), 'nl'));
    }

    /**
     * @test
     *
     * @dataProvider userRolesProvider
     */
    public function can_login_as_role_on_staging_environments(string $role): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->app['env'] = 'stage';

        $this
            ->post(route('account.login-as-test-user'), [
                'role' => $role,
            ])

            ->assertRedirect();

        $this->assertAuthenticated();
    }

    /**
     * @test
     *
     * @dataProvider userRolesProvider
     */
    public function does_not_have_the_option_to_login_with_test_users_on_production(string $role): void
    {
        $this->app['env'] = 'production';

        $this->post(route('account.login-as-test-user'), [
            'role' => $role,
        ]);

        $this->assertGuest();
    }

    public function userRolesProvider(): array
    {
        return [
            ['role' => 'admin'],
            ['role' => Role::TEACHER],
            ['role' => Role::INFORMATION_MANAGER],
            ['role' => Role::CONTENT_MANAGER],
        ];
    }
}
