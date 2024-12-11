<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolLog;
use App\Models\User;

class ToolLogSeeder extends BaseSeeder
{
    public function handle(): void
    {
        $allUsers = User::all();

        Tool::each(function (Tool $tool) use ($allUsers): void {
            if (!rand(0, 4)) {
                $this->advanceProgressBar();

                return;
            }

            foreach ($allUsers->random(rand(2, 5)) as $user) {
                ToolLog::factory()->count(rand(10, 20))->create([
                    'user_id'      => $user->id,
                    'tool_id'      => $tool->id,
                    'institute_id' => $user->isInformationManager() ? $user->institute->id : null,
                ]);
            }

            $this->advanceProgressBar();
        });
    }

    protected function getProgressBarCount(): int
    {
        return Tool::count();
    }
}
