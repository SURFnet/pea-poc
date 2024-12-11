<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Auth\Role;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\User> */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $createdAt;

        if ($this->faker->boolean()) {
            $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        return [
            'institute_id' => fn () => Institute::factory(),
            'external_id'  => null,
            'email'        => $this->faker->email(),
            'name'         => $this->faker->name(),
            'roles'        => $this->faker->randomElements(Role::toArray(), rand(1, 3)),
            'language'     => $this->faker->randomElement(['en', 'nl']),

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function withRoles(array $roles): Factory
    {
        return $this->state(fn () => [
            'roles' => $roles,
        ]);
    }

    public function superAdmin(): Factory
    {
        return $this->withRoles(array_values(Role::toArray()));
    }

    public function contentManager(): Factory
    {
        return $this->withRoles([Role::CONTENT_MANAGER]);
    }

    public function informationManager(): Factory
    {
        return $this->withRoles([Role::INFORMATION_MANAGER]);
    }

    public function teacher(): Factory
    {
        return $this->withRoles([Role::TEACHER]);
    }
}
