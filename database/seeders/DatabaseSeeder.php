<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            Bootstrapper::class,

            UserSeeder::class,

            PredefinedToolSeeder::class,
            ToolSeeder::class,
            CategorySeeder::class,
            ExperienceSeeder::class,
        ]);
    }
}
