<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InstituteTool\Status;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class InstituteToolFactory extends Factory
{
    /** @var string */
    protected $model = InstituteTool::class;

    public function definition()
    {
        $status = $this->faker->optional(80)->randomElement(Status::toArray());

        $data = [
            'institute_id' => fn () => Institute::factory(),
            'tool_id'      => fn ()      => Tool::factory(),

            'description_1'                => $this->faker->text(),
            'description_1_image_filename' => null,

            'description_2'                => $this->faker->text(),
            'description_2_image_filename' => null,

            'extra_information_title' => $this->faker->words(2, true),
            'extra_information'       => $this->faker->text(),

            'support_title_1' => $this->faker->words(2, true),
            'support_email_1' => $this->faker->email(),
            'support_title_2' => $this->faker->words(2, true),
            'support_email_2' => $this->faker->email(),

            'manual_title_1' => $this->faker->words(2, true),
            'manual_url_1'   => $this->faker->url(),
            'manual_title_2' => $this->faker->words(2, true),
            'manual_url_2'   => $this->faker->url(),

            'video_title_1' => $this->faker->words(2, true),
            'video_url_1'   => $this->faker->url(),
            'video_title_2' => $this->faker->words(2, true),
            'video_url_2'   => $this->faker->url(),

            'status' => $status,

            'published_at' => $this->faker->optional()->dateTimeThisYear(),
        ];

        if ($status === Status::PROHIBITED) {
            $data = array_merge($data, [
                'why_unfit'           => $this->faker->optional(75)->text(),
                'alternative_tool_id' => fn () => rand(0, 3) ? Tool::factory() : null,
            ]);
        }

        return $data;
    }

    public function withImages(): Factory
    {
        return $this->state(fn () => [
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

    public function status(string $status): Factory
    {
        return $this->state(fn () => [
            'status' => $status,
        ]);
    }

    public function published(bool $isPublished = true): Factory
    {
        return $this->state(fn () => [
            'published_at' => $isPublished ? $this->faker->dateTimeThisYear() : null,
        ]);
    }
}
