<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Tool\AuthenticationMethod;
use App\Enums\Tool\StoredData;
use App\Enums\Tool\SupportedStandard;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ToolFactory extends Factory
{
    /** @var string */
    protected $model = Tool::class;

    public function definition(): array
    {
        $storedData = $this->faker->randomElements(StoredData::toArray(), rand(1, 2));
        $otherStoredData = null;
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-2 hours');
        $updatedAt = $createdAt;
        $publishedAt = null;

        if ($this->faker->boolean()) {
            $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        if ($this->faker->boolean(80)) {
            $publishedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        if (in_array(StoredData::OTHER, $storedData)) {
            $otherStoredData = $this->faker->text(40);
        }

        return [
            'name'              => $this->faker->unique()->sentence(rand(2, 4)),
            'description_short' => $this->faker->text(),
            'image_filename'    => null,

            'description_long_1'           => $this->faker->optional()->text(),
            'description_1_image_filename' => null,

            'description_long_2'           => $this->faker->optional()->text(),
            'description_2_image_filename' => null,

            'info_url' => $this->faker->optional()->url(),

            'supported_standards'  => $this->faker->randomElements(SupportedStandard::toArray(), rand(1, 3)),
            'additional_standards' => $this->faker->text(40),

            'authentication_methods' => $this->faker->randomElements(AuthenticationMethod::toArray(), rand(1, 2)),

            'stored_data'       => $storedData,
            'other_stored_data' => $otherStoredData,

            'european_data_storage'           => $this->faker->boolean(),
            'surf_standards_framework_agreed' => $this->faker->boolean(),
            'has_processing_agreement'        => $this->faker->boolean(),

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
            'image_filename' => function (): string {
                $file = $this->faker->file(resource_path('seeding/tools/main'));

                return basename(Storage::putFile(Tool::$disk, $file));
            },
            'description_1_image_filename' => function (): string {
                $file = $this->faker->file(resource_path('seeding/tools/description'));

                return basename(Storage::putFile(Tool::$disk, $file));
            },
            'description_2_image_filename' => function (): string {
                $file = $this->faker->file(resource_path('seeding/tools/description'));

                return basename(Storage::putFile(Tool::$disk, $file));
            },
        ]);
    }
}
