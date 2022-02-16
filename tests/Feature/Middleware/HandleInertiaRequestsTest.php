<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\Assert;
use Tests\TestCase;

class HandleInertiaRequestsTest extends TestCase
{
    protected object $middleware;

    /** @test */
    public function has_the_app_name(): void
    {
        Config::set('app.name', 'Inertia Test');

        $this
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->where('app.name', 'Inertia Test')
            );
    }

    /** @test */
    public function has_the_environment(): void
    {
        $this->app['env'] = 'production';

        $this
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->where('app.isDev', false)
            );
    }

    /** @test */
    public function has_the_flash_notifications_from_the_session(): void
    {
        flash('Luke, I am your father', '');

        $this
            ->withSession([
                'flash_notification' => $this->app['flash']->messages,
            ])
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->has(
                        'flashNotifications.0',
                        fn (Assert $page) => $page
                            ->where('level', 'info')
                            ->where('message', 'Luke, I am your father')
                            ->etc()
                    )
            );
    }

    /** @test */
    public function has_the_authenticated_user(): void
    {
        $this
            ->actingAs($this->admin)
            ->get(route('home.index'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('home/Index')
                    ->where('currentUser', new UserResource($this->admin))
            );
    }

    /** @test */
    public function if_not_logged_in_has_no_user(): void
    {
        $this
            ->get(route('account.login'))

            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('account/Login')
                    ->where('currentUser', null)
            );
    }

    /** @test */
    public function has_the_version_of_the_assets_on_production(): void
    {
        $this->app['env'] = 'production';

        $expectedVersion = md5_file(public_path('dist/admin/mix-manifest.json'));

        $this
            ->get(route('account.login'))

            ->assertSee($expectedVersion);
    }
}
