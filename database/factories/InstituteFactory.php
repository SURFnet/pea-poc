<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Helpers\File;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\Institute> */
class InstituteFactory extends Factory
{
    public function definition()
    {
        return [
            'full_name_en' => $this->faker->company(),
            'full_name_nl' => $this->faker->company(),
            'short_name'   => $this->faker->lexify('???'),
            'domain'       => $this->faker->unique()->domainName(),

            'logo_full_filename'   => $this->selectRandomFile('seeding/institutes/full'),
            'logo_square_filename' => $this->selectRandomFile('seeding/institutes/square'),
            'banner_filename'      => $this->selectRandomFile('seeding/institutes/banner'),

            'homepage_title_en' => $this->faker->optional()->sentence(rand(2, 4)),
            'homepage_body_en'  => $this->faker->optional()->text(),
            'homepage_title_nl' => $this->faker->optional()->sentence(rand(2, 4)),
            'homepage_body_nl'  => $this->faker->optional()->text(),
        ];
    }

    private function selectRandomFile(string $path): string
    {
        return File::storeFromPath($this->faker->file(resource_path($path)), Institute::$disk);
    }
}
