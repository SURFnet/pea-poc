<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
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
        parent::setUp();

        $this->setUpUsers();
    }

    protected function setUpUsers(): void
    {
        $this->admin = User::factory()->superAdmin()->create([
            'name' => 'PAQT Admin',
        ]);

        $this->contentManager = User::factory()->contentManager()->create([
            'name' => 'PAQT Content Manager',
        ]);

        $this->informationManager = User::factory()->informationManager()->create([
            'name' => 'PAQT Information Manager',
        ]);

        $this->teacher = User::factory()->teacher()->create([
            'name' => 'PAQT Teacher',
        ]);
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
