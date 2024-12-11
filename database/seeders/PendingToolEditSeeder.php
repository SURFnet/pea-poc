<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PendingToolEdit;
use App\Models\Tool;
use App\Models\User;

class PendingToolEditSeeder extends BaseSeeder
{
    public function handle(): void
    {
        $allUsers = User::all();

        Tool::each(function (Tool $tool) use ($allUsers): void {
            if (!rand(0, 4)) {
                $this->advanceProgressBar();

                return;
            }

            foreach ($allUsers->random(rand(1, 3)) as $user) {
                PendingToolEdit::factory()->create([
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
