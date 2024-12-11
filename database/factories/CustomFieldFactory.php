<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Tool\Tabs;
use App\Models\CustomField;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFieldFactory extends Factory
{
    protected $model = CustomField::class;

    public function definition(): array
    {
        return [
            'title_en' => $this->faker->unique()->word(),
            'title_nl' => $this->faker->unique()->word(),
            'tab_type' => $this->faker->randomElement(Tabs::toArray()),

            'sortkey' => $this->faker->numberBetween(1, 100),

            'institute_id' => fn () => Institute::factory()->create(),
        ];
    }
}
