<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'description' => $this->faker->text(255),

            'institute_id' => fn () => Institute::factory(),
        ];
    }
}
