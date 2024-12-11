<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Way2Translate\Models\Language;

/** @extends Factory<\Modules\Way2Translate\Models\Language> */
class LanguageFactory extends Factory
{
    /** @var class-string<Language> */
    protected $model = Language::class;

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $this->faker->optional(0.5, $createdAt)->dateTimeBetween($createdAt, 'now');

        return [
            'locale'       => $this->faker->languageCode,
            'activated_at' => $this->faker->optional()->dateTimeBetween('-3 months', 'now'),

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function activated(): Factory
    {
        return $this->state(function () {
            return [
                'activated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            ];
        });
    }
}
