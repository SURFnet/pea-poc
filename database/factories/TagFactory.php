<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Tags\TagTypes;
use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Tag> */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->unique()->words(rand(1, 3), true),
            'type'         => $this->faker->randomElement(TagTypes::forTool()),
            'order_column' => $this->faker->randomNumber(),
        ];
    }

    public function withInstitute(?Institute $institute = null): Factory
    {
        return $this->state(fn () => [
            'institute_id' => $institute ?? Institute::factory()->create(),
            'type'         => $this->faker->randomElement(TagTypes::forInstituteTool()),
        ]);
    }
}
