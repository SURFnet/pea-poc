<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeatureFactory extends Factory
{
    /** @var string */
    protected $model = Feature::class;

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $createdAt;

        if ($this->faker->boolean()) {
            $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        return [
            'name' => $this->faker->name(),

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
