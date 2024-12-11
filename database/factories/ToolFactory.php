<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Helpers\Country;
use App\Helpers\File;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\Tool> */
class ToolFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $createdAt;
        $publishedAt = null;

        if ($this->faker->boolean()) {
            $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        if ($this->faker->boolean(80)) {
            $publishedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        return [
            'name'                                => $this->faker->unique()->sentence(rand(2, 4)),
            'supplier'                            => $this->faker->text(),
            'supplier_url'                        => $this->faker->url(),
            'description_short_en'                => $this->faker->text(),
            'description_short_nl'                => $this->faker->text(),
            'addons_en'                           => $this->faker->text(),
            'addons_nl'                           => $this->faker->text(),
            'system_requirements_en'              => $this->faker->text(),
            'system_requirements_nl'              => $this->faker->text(),
            'supplier_country'                    => $this->faker->optional()->randomElement(Country::getCodes()),
            'personal_data_en'                    => $this->faker->text(),
            'personal_data_nl'                    => $this->faker->text(),
            'privacy_policy_url'                  => $this->faker->url(),
            'model_processor_agreement_url'       => $this->faker->url(),
            'privacy_analysis'                    => $this->faker->text(),
            'supplier_agrees_with_surf_standards' => $this->faker->boolean(),
            'dtia_by_external_url'                => $this->faker->url(),
            'dpia_by_external_url'                => $this->faker->url(),
            'jurisdiction'                        => $this->faker->text(),
            'instructions_manual_1_url_en'        => $this->faker->url(),
            'instructions_manual_1_url_nl'        => $this->faker->url(),
            'instructions_manual_2_url_en'        => $this->faker->url(),
            'instructions_manual_2_url_nl'        => $this->faker->url(),
            'instructions_manual_3_url_en'        => $this->faker->url(),
            'instructions_manual_3_url_nl'        => $this->faker->url(),
            'support_for_teachers_en'             => $this->faker->text(),
            'support_for_teachers_nl'             => $this->faker->text(),
            'availability_surf'                   => $this->faker->text(),
            'accessibility_facilities_en'         => $this->faker->text(),
            'accessibility_facilities_nl'         => $this->faker->text(),
            'description_long_en'                 => $this->faker->text(),
            'description_long_nl'                 => $this->faker->text(),
            'use_for_education_en'                => $this->faker->text(),
            'use_for_education_nl'                => $this->faker->text(),
            'how_does_it_work_en'                 => $this->faker->text(),
            'how_does_it_work_nl'                 => $this->faker->text(),

            'logo_filename'    => null,
            'image_1_filename' => null,
            'image_2_filename' => null,

            'published_at' => $publishedAt,

            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function published(bool $isPublished = true): Factory
    {
        return $this->state(fn () => [
            'published_at' => $isPublished ? $this->faker->dateTimeThisYear : null,
        ]);
    }

    public function withImages(): Factory
    {
        return $this->state(fn () => [
            'logo_filename' => function (): string {
                return File::storeFromPath($this->faker->file(resource_path('seeding/tools/main')), Tool::$disk);
            },
            'image_1_filename' => function (): string {
                return File::storeFromPath($this->faker->file(resource_path('seeding/tools/description')), Tool::$disk);
            },
            'image_2_filename' => function (): string {
                return File::storeFromPath($this->faker->file(resource_path('seeding/tools/description')), Tool::$disk);
            },
        ]);
    }
}
