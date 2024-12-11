<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Institute;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\ToolLog> */
class ToolLogFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');
        $updatedAt = $createdAt;

        return [
            'tool_id'      => fn () => Tool::factory()->create(),
            'user_id'      => fn () => User::factory()->create(),
            'institute_id' => $this->faker->boolean() ? fn () => Institute::factory()->create() : null,

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function withInstitute(): self
    {
        return $this->state([
            'institute_id' => fn () => Institute::factory()->create(),
        ]);
    }

    public function withoutInstitute(): self
    {
        return $this->state([
            'institute_id' => null,
        ]);
    }
}
