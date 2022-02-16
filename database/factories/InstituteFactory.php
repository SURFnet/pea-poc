<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Institute;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class InstituteFactory extends Factory
{
    /** @var string */
    protected $model = Institute::class;

    public function definition()
    {
        return [
            'full_name'  => $this->faker->company(),
            'short_name' => $this->faker->lexify('???'),
            'domain'     => $this->faker->unique()->domainName(),

            'logo_full_filename'   => $this->selectRandomFile('seeding/institutes/full'),
            'logo_square_filename' => $this->selectRandomFile('seeding/institutes/square'),
            'banner_filename'      => $this->selectRandomFile('seeding/institutes/banner'),
        ];
    }

    private function selectRandomFile(string $path): Closure
    {
        return fn (): string => basename(
            Storage::putFile(Institute::$disk, $this->faker->file(resource_path($path)))
        );
    }
}
