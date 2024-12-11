<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\InstituteTool;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFieldValueFactory extends Factory
{
    protected $model = CustomFieldValue::class;

    public function definition(): array
    {
        return [
            'value_en' => $this->faker->unique()->word(),
            'value_nl' => $this->faker->unique()->word(),

            'institute_tool_id' => fn () => InstituteTool::factory()->create(),
            'custom_field_id'   => fn () => CustomField::factory()->create(),
        ];
    }
}
