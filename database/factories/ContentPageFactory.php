<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContentPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<ContentPage> */
class ContentPageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title_en'   => $this->faker->word(),
            'title_nl'   => $this->faker->word(),
            'slug'       => $this->faker->slug(),
            'body_en'    => $this->faker->paragraph(),
            'body_nl'    => $this->faker->paragraph(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
