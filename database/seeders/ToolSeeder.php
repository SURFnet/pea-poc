<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;

class ToolSeeder extends BaseSeeder
{
    private int $totalTools = 25;

    public function handle(): void
    {
        for ($index = 0; $index < $this->totalTools; $index++) {
            $tool = Tool::factory()->withImages()->create();

            if ($tool->is_published) {
                Institute::get()->random(rand(1, 4))->each(function (Institute $institute) use ($tool): void {
                    InstituteTool::factory()->for($institute)->for($tool)->create();
                });
            }

            $this->advanceProgressBar();
        }
    }

    protected function getProgressBarCount(): int
    {
        return $this->totalTools;
    }
}
