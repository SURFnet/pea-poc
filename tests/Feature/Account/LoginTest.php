<?php

declare(strict_types=1);

namespace Tests\Feature\Account;

use App\Http\Middleware\VerifyCsrfToken;
use Inertia\Testing\AssertableInertia as Assert;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function has_the_login_as_super_admin_button_on_development_environments(): void
    {
        $this
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->where('canLoginAsSuperAdmin', true)
            );
    }

    /** @test */
    public function can_login_as_super_admin_on_development_environments(): void
    {
        $this
            ->post(route('account.login-as-super-admin'));

        $this->assertAuthenticated();
    }

    /** @test */
    public function upon_login_the_locale_is_applied(): void
    {
        $this->admin->language = 'nl';
        $this->admin->save();

        $this
            ->post(route('account.login-as-super-admin'))

            ->assertRedirect(LaravelLocalization::localizeUrl(route('home.index'), 'nl'));
    }

    /** @test */
    public function can_login_as_super_admin_on_staging_environments(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->app['env'] = 'stage';

        $this
            ->post(route('account.login-as-super-admin'));

        $this->assertAuthenticated();
    }

    /** @test */
    public function does_not_have_the_login_as_super_admin_button_on_production(): void
    {
        $this->app['env'] = 'production';

        $this
            ->post(route('account.login-as-super-admin'));

        $this->assertGuest();
    }
}
