<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Tool;
use App\Models\User;

class ExperienceSeeder extends BaseSeeder
{
    public function handle(): void
    {
        $allUsers = User::all();

        Tool::each(function (Tool $tool) use ($allUsers): void {
            if (!rand(0, 4)) {
                $this->advanceProgressBar();

                return;
            }

            foreach ($allUsers->random(rand(1, 6)) as $user) {
                Experience::factory()->create([
                    'user_id' => $user->id,
                    'tool_id' => $tool->id,
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
