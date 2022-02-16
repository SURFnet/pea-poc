<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    /** @var string */
    protected $model = Experience::class;

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $createdAt;

        return [
            'tool_id' => fn () => Tool::factory()->create(),
            'user_id' => fn () => User::factory()->create(),

            'rating'  => $this->faker->numberBetween(1, 5),
            'title'   => $this->faker->optional(0.7)->sentence(),
            'message' => $this->faker->optional(0.7)->text(),

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
