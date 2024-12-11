<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Institute;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\PendingToolEdit> */
class PendingToolEditFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-30 minutes', 'now');
        $updatedAt = $createdAt;

        return [
            'tool_id'      => fn () => Tool::factory()->create(),
            'user_id'      => fn () => User::factory()->create(),
            'institute_id' => $this->faker->boolean() ? fn () => Institute::factory()->create() : null,

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
