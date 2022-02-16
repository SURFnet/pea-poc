<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Institute;
use App\Models\Tool;

class CategorySeeder extends BaseSeeder
{
    private array $defaultCategories = [
        'Collaboration',
        'CreationEdu Games',
        'Instruction',
        'Presentation',
        'Video',
        'Virtual',
    ];

    public function handle(): void
    {
        Institute::each(function (Institute $institute): void {
            foreach ($this->defaultCategories as $categoryName) {
                $category = Category::factory()->create([
                    'name'         => $categoryName,
                    'institute_id' => $institute->id,
                ]);

                $category->tools()->attach(Tool::get()->random(rand(1, 4)));
            }

            $this->advanceProgressBar();
        });
    }

    protected function getProgressBarCount(): int
    {
        return Institute::count();
    }
}
