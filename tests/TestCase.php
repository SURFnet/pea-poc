<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\Auth\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected User $admin;

    protected User $contentManager;

    protected User $informationManager;

    protected User $teacher;

    protected function setUp(): void
    {
        $this->setRoutingLocale();

        parent::setUp();

        $this->setUpUsers();
    }

    /**
     * During the test setup, the called route is not yet known. This means no language can be set.
     * When a request is made during a test, this results in a 404 - without the prefix set the localized route does
     * not seem to exist.
     *
     * @see: https://github.com/mcamara/laravel-localization?tab=readme-ov-file#testing
     */
    private function setRoutingLocale(): void
    {
        putenv(\Mcamara\LaravelLocalization\LaravelLocalization::ENV_ROUTE_KEY . '=' . 'en');
    }

    protected function setUpUsers(): void
    {
        $this->admin = User::factory()->superAdmin()->create([
            'name' => 'PAQT Admin',
        ]);

        $this->contentManager = User::factory()->contentManager()->create([
            'name' => 'PAQT ' . Role::CONTENT_MANAGER,
        ]);

        $this->informationManager = User::factory()->informationManager()->create([
            'name' => 'PAQT ' . Role::INFORMATION_MANAGER,
        ]);

        $this->teacher = User::factory()->teacher()->create([
            'name' => 'PAQT ' . Role::TEACHER,
        ]);
    }

    public function actingAs(UserContract $user, $guard = null)
    {
        return parent::actingAs($user, $guard)
            ->session(['locale' => 'en']);
    }

    /** @return mixed */
    protected function getProperty(object &$instance, string $property)
    {
        $reflection = new ReflectionClass(get_class($instance));
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
