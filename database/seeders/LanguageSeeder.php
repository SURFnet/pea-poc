<?php

declare(strict_types=1);

namespace Database\Seeders;

use Modules\Way2Translate\Models\Language;

class LanguageSeeder extends BaseSeeder
{
    public function handle(): void
    {
        Language::factory()->activated()->create(['locale' => 'nl']);

        $this->advanceProgressBar();
    }

    protected function getProgressBarCount(): int
    {
        return 1;
    }
}
