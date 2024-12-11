<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Status;
use App\Models\Institute;
use App\Models\Tool;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\InstituteTool> */
class InstituteToolFactory extends Factory
{
    public function definition()
    {
        /** @var DateTime|null */
        $publishedAt = $this->faker->optional()->dateTimeThisYear();
        $status = $this
            ->faker
            ->optional($publishedAt ? 100 : 80)
            ->randomElement([Status::ALLOWED, Status::ALLOWED_UNDER_CONDITIONS, Status::DISALLOWED]);

        $data = [
            'institute_id' => fn () => Institute::factory(),
            'tool_id'      => fn () => Tool::factory(),

            'status'        => $status,
            'conditions_en' => $this->faker->text(),
            'conditions_nl' => $this->faker->text(),

            'links_with_other_tools_en' => $this->faker->text(),
            'links_with_other_tools_nl' => $this->faker->text(),
            'sla_url'                   => $this->faker->url(),

            'privacy_contact'         => $this->faker->text(),
            'privacy_evaluation_url'  => $this->faker->url(),
            'security_evaluation_url' => $this->faker->url(),
            'data_classification'     => $this->faker->randomElement(DataClassification::toArray()),

            'how_to_login_en'           => $this->faker->text(),
            'how_to_login_nl'           => $this->faker->text(),
            'availability_en'           => $this->faker->text(),
            'availability_nl'           => $this->faker->text(),
            'licensing_en'              => $this->faker->text(),
            'licensing_nl'              => $this->faker->text(),
            'request_access_en'         => sprintf('<a href="%1$s">%1$s</a>', $this->faker->url()),
            'request_access_nl'         => sprintf('<a href="%1$s">%1$s</a>', $this->faker->url()),
            'instructions_en'           => $this->faker->text(),
            'instructions_nl'           => $this->faker->text(),
            'instructions_manual_1_url' => $this->faker->url(),
            'instructions_manual_2_url' => $this->faker->url(),
            'instructions_manual_3_url' => $this->faker->url(),

            'faq_en'                     => $this->faker->text(),
            'faq_nl'                     => $this->faker->text(),
            'examples_of_usage_en'       => $this->faker->text(),
            'examples_of_usage_nl'       => $this->faker->text(),
            'additional_info_heading_en' => $this->faker->text(),
            'additional_info_heading_nl' => $this->faker->text(),
            'additional_info_text_en'    => $this->faker->text(),
            'additional_info_text_nl'    => $this->faker->text(),

            'published_at' => $publishedAt,
        ];

        if ($status === Status::DISALLOWED) {
            $data = array_merge($data, [
                'why_unfit_en' => $this->faker->optional(75)->text(),
                'why_unfit_nl' => $this->faker->optional(75)->text(),
            ]);
        }

        return $data;
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
