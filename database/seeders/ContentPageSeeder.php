<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ContentPage;

class ContentPageSeeder extends BaseSeeder
{
    protected function handle(): void
    {
        ContentPage::factory(10)->create();
        $this->advanceProgressBar();
    }

    protected function getProgressBarCount(): int
    {
        return 1;
    }
}
